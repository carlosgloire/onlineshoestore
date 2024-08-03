<?php
session_start(); // Start the session

require_once('mail/database.php');

// Check if session variables are set and not empty
if (!empty($_SESSION['fname']) && !empty($_SESSION['lname']) && !empty($_SESSION['email']) && !empty($_SESSION['phone']) && !empty($_SESSION['country']) && !empty($_SESSION['city']) && !empty($_SESSION['password'])) {
        
    $query = $mysqli->prepare("SELECT * FROM users WHERE firstname = ? OR lastname = ? OR email = ? ");
    $query->bind_param("sss", $_SESSION['fname'],$_SESSION['lname'],$_SESSION['email']);
    $query->execute();
    $userexist = $query->get_result()->fetch_assoc();
    if($userexist){
        echo '<script>alert("This account has already been created you cannot duplicate it ");</script>';
        echo '<script>window.location.href="../public/index.php";</script>';
    }else{
        $query = $mysqli->prepare('INSERT INTO users (firstname, lastname, email, phone, country,  password,city,photo) VALUES (?, ?, ?, ?, ?, ?, ?,?)');

        $query->bind_param('ssssssss', $_SESSION['fname'], $_SESSION['lname'], $_SESSION['email'], $_SESSION['phone'], $_SESSION['country'],  $_SESSION['password'],$_SESSION['city'],$_SESSION['filename']);
        $query->execute();
    
        if ($query->affected_rows > 0) {
            echo '<script>alert("Account created successfully ");</script>';
            echo '<script>window.location.href="../public/index.php";</script>';
        } else {
            // Handle error
            echo "Error creating account: " . $mysqli->error;
        }
    
        // Close the statement
        $query->close();
    }
      
    
} else {
    // Handle empty session variables
    echo "Registration failed, please try again.";
}
?>
