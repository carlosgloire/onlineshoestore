<?php
$error = null;
$success = null;
$mysqli = require(__DIR__ . "/../controllers/mail/database.php");

if (isset($_POST['send'])) {
    if (isset($_POST['message']) && isset($_POST['subject'])) {
        $message = nl2br(htmlspecialchars($_POST['message']));
        $subject = htmlspecialchars($_POST['subject']);

        // Fetch all form teachers' emails
        $query = $mysqli->prepare("SELECT email FROM mailNewsletter");
        $query->execute();
        $result = $query->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);

        if (!$users) {
            $error = "No email found. Please try again.";
        } elseif (empty($subject) || empty($message)) {
            $error = "Please fill in all fields.";
        } else {
            $mail = require __DIR__ . "/mail/mailer.php";
            $mail->setFrom("noreply@example.com", "Online shoes storing management system");
            $mail->Subject = $subject;
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
                        white-space: pre-wrap; 
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <p>$message</p>
                </div>
            </body>
            </html>
            END;

            $mail->Body = $email_body;
            $mail->isHTML(true);

            $errors = [];
            foreach ($users as $user) {
                $mail->clearAllRecipients();
                $mail->addAddress($user['email']);

                try {
                    $mail->send();
                } catch (Exception $e) {
                    $errors[] = "The message could not be sent to {$user['email']}. Mailer error: {$mail->ErrorInfo}. No connection.";
                }
            }

            if (empty($errors)) {
                $success = "Message sent to all emails.";
            } else {
                $error = implode("<br>", $errors);
            }
        }
    }
}
?>
