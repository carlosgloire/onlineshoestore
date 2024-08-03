<?php
$error=null;
$success=null;
$mysqli = require( __DIR__ . "/mail/database.php");
if(isset($_POST['send'])){
    
 if(isset($_POST['email'])){
    $email=htmlspecialchars($_POST['email']);
    $query = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $mail_query = $query->get_result()->fetch_assoc();
    if(empty($email)){
        $error ="Please enter your email address";
    }
    elseif(!$mail_query){
        $error ="We didn't find an account with that email. Please try again or create a new account";
    }else{
                
        $token = bin2hex(random_bytes(16));

        $token_hash = hash("sha256", $token);

        $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

        $sql = "UPDATE users
                SET reset_token_hash = ?,
                    reset_token_expires_at = ?
                WHERE email = ?";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param("sss", $token_hash, $expiry, $email);

        $stmt->execute();

        if ($mysqli->affected_rows) {

            $mail = require __DIR__ . "/mail/mailer.php";

            $mail->setFrom("noreply@example.com","ONLINE SHOES STORING MANAGEMENT SYSTEM");
            $mail->addAddress($email);
            $mail->Subject = "Password Reset";
            $mail->Body = <<<END

            <html>
            <head>
                <style>
                
                    body {
                        font-family: arial;
                        background-color: #f5f5f5;
                        padding: 20px;
                    }
                    .container {
                        width: 35%;
                        padding: 40px 60px;
                        background-color: #fff;
                        box-shadow: 0px 1px 10px rgba(0, 0, 0, 0.0008);
                        font-family: arial;

                    }
                    .button {
                        font-family: arial;
                        padding: 10px 20px;
                        font-size: 16px;
                        background-color: #141b1fda;
                        text-decoration: none;
                        border-radius: 5px;
                        text-align:left;
                    }
                    a{
                        color:white;
                     }
                </style>

            </head>
            <body>
                <div class="container">
                    <p>Hello,</p>
                    <p>We've received a request to reset your password. Click the button below to proceed:</p>
                    <p style="text-align: center;"><a class="button" style="color:white" href="http://localhost/onlineShoeStore/templates/reset-password.php?token=$token">Reset Password</a></p>
                    <p>If you didn't request this, please ignore this email.</p>
                </div>
            </body>
            </html>

            END;

            try {

                $mail->send();

            } catch (Exception $e) {

                $error= "Message could not be sent. Mailer error: {$mail->ErrorInfo}";

            }

        }

        $success="Message sent to this email, please verify your inbox.";
    }
 }   


}