<?php
require_once './inc/function.php';
session_start();

// Fonction de validation
function validateArticleData($id, $titre, $contenu) {
    $errors = [];
    
    if (!$id || $id <= 0) {
        $errors[] = "ID d'article invalide";
    }
    
    if (empty(trim($titre))) {
        $errors[] = "Le titre est obligatoire";
    } elseif (strlen($titre) > 255) {
        $errors[] = "Le titre ne peut pas dépasser 255 caractères";
    }
    
    if (empty(trim($contenu))) {
        $errors[] = "Le contenu est obligatoire";
    }
    
    return $errors;
}

// Fonction de gestion d'image
function handleImageUpload($file, $articleId, $pdo) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    // Types MIME autorisés
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception("Type d'image non autorisé");
    }
    
    // Taille maximale : 2MB (cohérent avec création d'article)
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new Exception("L'image ne doit pas dépasser 2MB");
    }

    // map allowed mime types to extensions
    $allowedTypes = ['image/jpeg' => '.jpg', 'image/png' => '.png', 'image/webp' => '.webp', 'image/gif' => '.gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!array_key_exists($mimeType, $allowedTypes)) {
        throw new Exception("Type d'image non autorisé");
    }

    // Use absolute, reliable upload directory
    $uploadDir = __DIR__ . '/../images/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            throw new Exception('Impossible de créer le dossier d\'uploads');
        }
    }
    if (!is_writable($uploadDir)) {
        @chmod($uploadDir, 0755);
        if (!is_writable($uploadDir)) {
            throw new Exception('Dossier d\'uploads non accessible en écriture (permissions)');
        }
    }

    // create a safe unique filename with proper extension
    $ext = $allowedTypes[$mimeType];
    try {
        $fileName = time() . '_' . bin2hex(random_bytes(6)) . $ext;
    } catch (Exception $e) {
        $fileName = time() . '_' . uniqid() . $ext;
    }
    $targetPath = $uploadDir . $fileName;

    // Compression si fonction disponible, sinon fallback à move_uploaded_file
    if (function_exists('compressImage')) {
        $ok = compressImage($file['tmp_name'], $targetPath, 70);
        if ($ok !== true) {
            throw new Exception('Erreur lors de l\'enregistrement de l\'image : ' . (string)$ok);
        }
    } else {
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Échec du déplacement du fichier uploadé');
        }
    }
    @chmod($targetPath, 0644);
    
    // Supprimer l'ancienne image
    $stmtOld = $pdo->prepare("SELECT path FROM image_info WHERE idinfo = :id");
    $stmtOld->execute(['id' => $articleId]);
    $old = $stmtOld->fetch(PDO::FETCH_ASSOC);
    
    if ($old && !empty($old['path'])) {
        $oldPath = __DIR__ . '/../' . $old['path'];
        if (file_exists($oldPath)) {
            @unlink($oldPath);
        }
    }
    
    return "images/" . $fileName;
}

// Vérification de l'authentification
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /view/login.php");
    exit();
}

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}

// Récupération et validation des données
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$titre = trim($_POST['titre'] ?? '');
$contenu = trim($_POST['contenu'] ?? '');

// Validation
$errors = validateArticleData($id, $titre, $contenu);

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: editArticle.php?id=" . $id);
    exit();
}

try {
    // Mise à jour de l'article
    $stmt = $pdo->prepare("
        UPDATE info 
        SET titre = :titre, 
            contenu = :contenu,
            date_modification = NOW()
        WHERE id = :id
    ");
    
    $stmt->execute([
        'titre' => $titre,
        'contenu' => $contenu,
        'id' => $id
    ]);
    
    // Gestion de l'image
    // Debug logging for upload
    if (!empty($_FILES)) {
        error_log('updateArticle: _FILES keys = ' . json_encode(array_keys($_FILES)));
    } else {
        error_log('updateArticle: _FILES is empty');
    }
    if (isset($_FILES['image'])) {
        error_log('updateArticle: image error=' . intval($_FILES['image']['error']) . ' size=' . intval($_FILES['image']['size']));
        $imagePath = handleImageUpload($_FILES['image'], $id, $pdo);
        
        if ($imagePath) {
            // Vérifier si une image existe déjà
            $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM image_info WHERE idinfo = :id");
            $stmtCheck->execute(['id' => $id]);
            $exists = $stmtCheck->fetchColumn();
            
            if ($exists) {
                $stmtImg = $pdo->prepare("
                    UPDATE image_info 
                    SET path = :path 
                    WHERE idinfo = :id
                ");
            } else {
                $stmtImg = $pdo->prepare("
                    INSERT INTO image_info (idinfo, path) 
                    VALUES (:id, :path)
                ");
            }
            
            $stmtImg->execute([
                'path' => $imagePath,
                'id' => $id
            ]);
        }
    }
    
    $_SESSION['success'] = "Article modifié avec succès !";
    header("Location: dashboard.php?update=success");
    exit();

} catch (Exception $e) {
    error_log("Erreur updateArticle: " . $e->getMessage());
    $_SESSION['error'] = $e->getMessage();
    header("Location: editArticle.php?id=" . $id);
    exit();
}
?>