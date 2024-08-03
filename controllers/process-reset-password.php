<?php
$error=null;
if(isset($_POST['reset'])){

    $token = $_POST["token"];

    $token_hash = hash("sha256", $token);
    
    $mysqli = require __DIR__ . "/mail/database.php";
    
    $sql = "SELECT * FROM users
            WHERE reset_token_hash = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("s", $token_hash);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $user = $result->fetch_assoc();
    
    if ($user === null) {
        echo '<script>alert("Token not found");</script>';
        echo '<script>window.location.href="../templates/";</script>';
        exit;
    }
    
    elseif (strtotime($user["reset_token_expires_at"]) <= time()) {
        echo '<script>alert("Token has expired");</script>';
        echo '<script>window.location.href="../templates/";</script>';
        exit;
    }
    elseif (empty($_POST["password"])  ||  empty($_POST["password_confirmation"])) {
        $error= "Please complete all the fields";
    }
    elseif (!preg_match("#[a-zA-Z]+#", $_POST['password']) ||
        !preg_match("#[0-9]+#", $_POST['password']) ||
        !preg_match("#[\!\@\#\$\%\^\&\*\(\)\_\+\-\=\[\]\{\}\;\:\'\"\,\<\>\.\?\/\`\~\\\|\ ]+#", $_POST['password'])) {
        $error = "Your password must contain at least one letter, one number, and one special character.";
} 
        
    elseif ($_POST["password"] !== $_POST["password_confirmation"]) {
        $error= "Passwords must match";
    }else{
        $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    
        $sql = "UPDATE users
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