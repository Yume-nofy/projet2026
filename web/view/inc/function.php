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

?>