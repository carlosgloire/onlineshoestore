<?php
$error=null;
$success=null;
$mysqli = require( __DIR__ . "../database.php");
if(isset($_POST['send'])){
 if(isset($_POST['email'])){
    $email=htmlspecialchars($_POST['email']);
    $query = $mysqli->prepare("SELECT * FROM principals WHERE mail = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $mail_query = $query->get_result()->fetch_assoc();

    if(!$mail_query){
        $error ="We didn't find an account with that email. Please try again or create a new account";
    }else{
                
        $token = bin2hex(random_bytes(16));

        $token_hash = hash("sha256", $token);

        $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

        $sql = "UPDATE principals
                SET reset_token_hash = ?,
                    reset_token_expires_at = ?
                WHERE mail = ?";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param("sss", $token_hash, $expiry, $email);

        $stmt->execute();

        if ($mysqli->affected_rows) {

            $mail = require __DIR__ . "/mailer.php";

            $mail->setFrom("noreply@example.com");
            $mail->addAddress($email);
            $mail->Subject = "Password Reset";
            $mail->Body = <<<END

            <html>
            <head>
                <style>
                
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        padding: 20px;
                    }
                    .container {
                    
                        background-color: #fff;
                        padding: 30px;
                        border-radius: 8px;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    }
                    .button {
                        padding: 10px 20px;
                        font-size: 16px;
                        background-color: #007bff;
                        color: #fff;
                        text-decoration: none;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <p>Hello,</p>
                    <p>We've received a request to reset your password. Click the button below to proceed:</p>
                    <p style="text-align: center;"><a class="button" href="http://localhost/armel-dashboard/mail/reset-password.php?token=$token">Reset Password</a></p>
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