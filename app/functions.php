<?php
function createSlug($str) {
    // 1. Convertir en minuscules et enlever les accents
    $str = mb_strtolower($request, 'UTF-8');
    $str = str_replace(['é', 'è', 'à', 'ç', 'î'], ['e', 'e', 'a', 'c', 'i'], $str);
    
    // 2. Remplacer tout ce qui n'est pas lettre ou chiffre par un tiret
    $str = preg_replace('/[^a-z0-9\-]/', '-', $str);
    
    // 3. Enlever les doubles tirets
    $str = preg_replace('/-+/', '-', $str);
    
    return trim($str, '-');
}
?>