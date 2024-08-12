<?php
require_once('../controllers/database/db.php');
$error=null;
if(isset($_POST['login'])){
   $mail=htmlspecialchars($_POST['mail']);
   $password=htmlspecialchars($_POST['password']);
   //Connect the user with their name or email
   if(empty($_POST['mail']) || empty($_POST['password'])){
      $error ="Please complete all the fields!";
   }
   if(empty($_POST['mail'])){
      $error ="Please  enter email address!";
   }
   elseif(empty($_POST['password'])){
      $error ="Please enter the password!";
   }
   else{
      $request = $db->prepare("SELECT email,password,user_id,role FROM users WHERE email = :email ");
      $request->bindValue(':email', $mail);
      $request->execute();
      $user = $request->fetch(PDO::FETCH_ASSOC);
      if($user){
         if (password_verify($password,$user['password'])) {
            $_SESSION['user_id']=$user['user_id'];
            $_SESSION['role']=$user['role'];
            $_SESSION['user']=$user;
            header("location: ../templates/");
            exit;
         }
         else{
            $error ="Email  or Password is incorrect!";
         }
      } else {
         $error = "No user in the system with this email address.";
      }
   }

}

?>