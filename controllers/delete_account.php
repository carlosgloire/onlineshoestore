<?php
session_start();
require_once('../controllers/database/db.php');

// Initialize variables
$error = null;

// Handle form submission
if (isset($_POST['delete'])) {
    // Request to get the password from the database
    $requete = $db->prepare("SELECT password FROM users WHERE user_id = :user_id");
    $requete->execute(['user_id' => $_SESSION['user_id']]);
    $get_password = $requete->fetch();

    $password2 = htmlspecialchars($_POST['password2']);   
    if (empty($password2)) {
        $error = "Please enter the password to delete your account.";
    } elseif (!password_verify($password2, $get_password['password'])) {
        $error = "The password you entered is incorrect.";
    } else {
        // Verify user ID and delete the account
        $recup_id = $db->prepare("SELECT user_id FROM users WHERE user_id = :user_id");
        $recup_id->execute(['user_id' => $_SESSION['user_id']]);
        $user_id = $recup_id->fetch();
        
        if ($recup_id->rowCount() > 0) {
            $delete_account = $db->prepare("DELETE FROM users WHERE user_id = :user_id");
            $delete_account->execute(['user_id' => $user_id['user_id']]);
            
            // Destroy the session after deleting the account
            session_destroy();
            
            echo '<script>alert("Account successfully deleted.");</script>';
            echo '<script>window.location.href="../templates/";</script>'; // Redirect to logout page after account deletion
            exit;
        } else {
            $error = "No account found.";
        }
    }
}
?>
