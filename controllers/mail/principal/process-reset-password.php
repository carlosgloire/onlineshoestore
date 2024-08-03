<?php

if(isset($_POST['reset'])){

    $token = $_POST["token"];

    $token_hash = hash("sha256", $token);
    
    $mysqli = require __DIR__ . "../database.php";
    
    $sql = "SELECT * FROM principals
            WHERE reset_token_hash = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("s", $token_hash);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $user = $result->fetch_assoc();
    
    if ($user === null) {
        die("token not found");
    }
    
    elseif (strtotime($user["reset_token_expires_at"]) <= time()) {
        die("token has expired");
    }
    
     // Validate password complexity
     elseif (!preg_match("#[a-zA-Z]+#", $_POST['password']) ||
     !preg_match("#[0-9]+#", $_POST['password']) ||
     !preg_match("#[-_@%&*!$^]+#", $_POST['password'])) {
    $error = "Your password must contain at least one letter, one number, and one of these special characters: - _ @ % & * ! $ ^";
    }
        
    elseif ($_POST["password"] !== $_POST["password_confirmation"]) {
        $error= "Passwords must match";
    }else{
        $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    
        $sql = "UPDATE principals
                SET password = ?,
                    reset_token_hash = NULL,
                    reset_token_expires_at = NULL
                WHERE user_id = ?";
        
        $stmt = $mysqli->prepare($sql);
        
        $stmt->bind_param("ss", $password_hash, $user["user_id"]);
        
        $stmt->execute();
        
          echo '<script>alert("Password updated you can now login");</script>';
        echo '<script>window.location.href="../pages/login.php";</script>';
        exit;
    }
    
    
}