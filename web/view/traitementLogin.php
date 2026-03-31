<?php
session_start();
include './inc/function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    
    if (verifyPassword($username, $password)) {
        
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;
        
        header("Location:dashboardAdmin.php");

    } else {
        
        header("Location: index.php?error=invalid");
        exit();
    }
}
?>