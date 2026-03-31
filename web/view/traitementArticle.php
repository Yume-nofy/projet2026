<?php
require_once './inc/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre = $_POST['titre'] ?? '';
    $contenu = $_POST['contenu'] ?? '';
    $idRegion = $_POST['idRegion'] ?? null;

    if (empty($titre) || empty($contenu) || empty($idRegion)) {
        echo($idRegion);
        die("Tous les champs sont obligatoires !");

    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO info (titre, contenu, region_id)
            VALUES (:titre, :contenu, :region)
        ");

        $stmt->execute([
            'titre' => $titre,
            'contenu' => $contenu,
            'region' => $idRegion
        ]);

        $idInfo = $pdo->lastInsertId();
        // Debug logging for upload issues
        if (!empty($_FILES)) {
            error_log('traitementArticle: _FILES keys = ' . json_encode(array_keys($_FILES)));
        } else {
            error_log('traitementArticle: _FILES is empty');
        }
        if (isset($_FILES['image'])) {
            error_log('traitementArticle: image error=' . intval($_FILES['image']['error']) . ' size=' . intval($_FILES['image']['size']));
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

            $uploadDir = __DIR__ . '/../images/';

            if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                die("Image trop lourde !");
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowedTypes)) {
                die("Format non autorisé !");
            }

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // determine extension from mime and create a unique filename
            $ext = '.jpg';
            if ($mime === 'image/png') $ext = '.png';
            if ($mime === 'image/webp') $ext = '.webp';
            try {
                $fileName = time() . '_' . bin2hex(random_bytes(4)) . $ext;
            } catch (Exception $e) {
                $fileName = time() . '_' . uniqid() . $ext;
            }
            $targetPath = $uploadDir . $fileName;

            $ok = compressImage($_FILES['image']['tmp_name'], $targetPath, 70);
            if ($ok !== true) {
                die("Erreur lors de l'enregistrement de l'image : " . (string)$ok);
            }

            @chmod($targetPath, 0644);

            $stmtImg = $pdo->prepare("
                INSERT INTO image_info (idinfo, path)
                VALUES (:idinfo, :path)
            ");

            $stmtImg->execute([
                'idinfo' => $idInfo,
                // store web-relative path
                'path' => 'images/' . $fileName
            ]);
        }

        header("Location: dashboardAdmin.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}