<?php
$error = null;
$success = null;
$mysqli = require(__DIR__ . "/mail/database.php"); // call to the database 

if (isset($_POST['send'])) {
    if (isset($_POST['message']) && isset($_POST['subject']) && isset($_POST['email']) && isset($_POST['noms'])) {
        $message = nl2br(htmlspecialchars($_POST['message']));
        $subject = nl2br(htmlspecialchars($_POST['subject']));
        $noms = htmlspecialchars($_POST['noms']);
        $sender_email = htmlspecialchars($_POST['email']);
        
        if (empty($subject) || empty($message) || empty($sender_email) || empty($noms)) {
            echo '<script>alert("Please complete all fields");</script>';
            echo '<script>window.location.href="../templates/";</script>';
            exit;
        } elseif (!filter_var($sender_email, FILTER_VALIDATE_EMAIL)) {
            echo '<script>alert("Your email is incorrect");</script>';
            echo '<script>window.location.href="../templates/";</script>';
            exit;
        }else {
            $mail = require __DIR__ . "/mail/mailer.php";
            $mail->setFrom($sender_email, $noms); // Set sender to the email entered in the form
            $mail->Subject = html_entity_decode($subject); // Decode HTML entities in subject
            $mail->CharSet = 'UTF-8'; // Set charset to UTF-8

            // Create the email body with the message from the textarea
            $email_body = <<<END
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
                        white-space: pre-wrap; /* Preserve white space and line breaks */
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <p><strong>Name:</strong> $noms</p>
                    <p><strong>Email:</strong> $sender_email</p>
                    <p><strong>Message:</strong><br>$message</p>
                </div>
            </body>
            </html>
            END;

            $mail->Body = $email_body;
            $mail->isHTML(true);
            
            // Set the recipient to the admin email
            $mail->addAddress('ndayisabarenzaho@gmail.com', 'Admin'); // Admin's email address

            try {
                $mail->send();
                echo '<script>alert("Your message has been sent successfully, you will be replied via your email");</script>';
                echo '<script>window.location.href="../templates/";</script>';
                exit;
            } catch (Exception $e) {
                echo "<script>alert('The message was not sent');</script>";
                echo '<script>window.location.href="../templates/";</script>';
                exit;
            }
        }
    }
}
?>
