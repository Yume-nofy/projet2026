<?php 
  require_once __DIR__ .'/../config/db.php';

  function getPwd($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT pwd FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetchColumn();
  }
  
  function verifyPassword($username, $password) {
    $hash = getPwd($username);
    
    if (!$hash) {
        return false;
    }
    
    return password_verify($password, $hash);
}

function createUser($username, $password) {
    global $pdo;
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, pwd) VALUES (:username, :pwd)");
    return $stmt->execute([
        'username' => $username,
        'pwd' => $hashedPassword
    ]);
}
function getAllPays($pdo) {
    $stmt = $pdo->query("SELECT * FROM pays");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllRegions($pdo) {
    $stmt = $pdo->query("SELECT * FROM region");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllArticles($pdo) {
    $stmt = $pdo->query("
        SELECT 
            info.id,
            info.titre,
            info.date_publication,
            info.contenu,
            region.nom AS region,
            pays.nom AS pays
        FROM info
        JOIN region ON info.region_id = region.id
        JOIN pays ON region.pays_id = pays.id
        ORDER BY info.date_publication DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function compressImage($source, $destination, $quality = 70) {
    $info = @getimagesize($source);
    if ($info === false) {
        return "Source non valide ou introuvable";
    }

    $destDir = dirname($destination);
    if (!is_dir($destDir)) {
        if (!mkdir($destDir, 0755, true) && !is_dir($destDir)) {
            return "Impossible de créer le dossier de destination";
        }
    }
    if (!is_writable($destDir)) {
        @chmod($destDir, 0755);
        if (!is_writable($destDir)) {
            return "Dossier de destination non accessible en écriture";
        }
    }

    $saved = false;
    $image = null;
    try {
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
            if ($image !== false) $saved = imagejpeg($image, $destination, $quality);

        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
            if ($image !== false) $saved = imagepng($image, $destination, 6); // compression 0-9

        } elseif ($info['mime'] == 'image/webp') {
            $image = imagecreatefromwebp($source);
            if ($image !== false) $saved = imagewebp($image, $destination, $quality);
        } else {
            return "Format d'image non supporté";
        }
    } catch (Exception $e) {
        return "Erreur lors du traitement de l'image: " . $e->getMessage();
    }

    if ($image && is_resource($image)) {
        imagedestroy($image);
    }

    if ($saved !== true && $saved !== 1) {
        return "Échec lors de l'écriture du fichier image";
    }

    return true;
}
function createSlug($str) {
    // 1. Convertir en minuscules
    $str = mb_strtolower($str, 'UTF-8');

    // 2. Translitérer les caractères UTF-8 vers ASCII si possible
    if (function_exists('iconv')) {
        $trans = @iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        if ($trans !== false) {
            $str = $trans;
        }
    }

    // 3. Remplacer tout ce qui n'est pas lettre ou chiffre par un tiret
    $str = preg_replace('/[^a-z0-9]+/', '-', $str);

    // 4. Enlever les doubles tirets
    $str = preg_replace('/-+/', '-', $str);

    return trim($str, '-');
}
?>