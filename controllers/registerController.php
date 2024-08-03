<?php
session_start();
$error = null;
$success = null;

$mysqli = require(__DIR__ . "/mail/database.php");



if (isset($_POST['register'])) {
    $firstname = htmlspecialchars($_POST['fname']);
    $lastname = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $country = htmlspecialchars($_POST['country']);
    $city = htmlspecialchars($_POST['city']);
    $password = htmlspecialchars($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $filename = $_FILES["uploadfile"]["name"];
    $filesize = $_FILES["uploadfile"]["size"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "../templates/profile_photo/" . $filename;
    $allowedExtensions = ['png', 'jpg', 'jpeg'];
    $pattern = '/\.(' . implode('|', $allowedExtensions) . ')$/i';

    $query = $mysqli->prepare("SELECT * FROM users WHERE email = ? ");
    $query->bind_param("s", $email);
    $query->execute();
    $mail_query = $query->get_result()->fetch_assoc();
    if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($country) || empty($password) || empty($password)) {
        $error = "Please complete all fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Your email is incorrect";
    } elseif (!preg_match("#^[+]+[0-9]{12}$#", $_POST['phone'])) {
        $error = "Please write the phone number with the country code Ex:+1 000 000 000.";
    } elseif ($country =='select') {
        $error = "Please select the country";
    } elseif (!preg_match($pattern, $_FILES['uploadfile']['name']) && !empty($_FILES['uploadfile']['name'])) {
        $error = "Your file must be in \"jpg, jpeg or png\" format";
    } elseif ($filesize > 5000000) {
        $error = "Your file must not exceed 5Mb";
    } elseif (!move_uploaded_file($tempname, $folder)) {
        $error = "Error while uploading";
    } elseif (!preg_match("#[a-zA-Z]+#", $_POST['password']) ||
              !preg_match("#[0-9]+#", $_POST['password']) ||
              !preg_match("#[\!\@\#\$\%\^\&\*\(\)\_\+\-\=\[\]\{\}\;\:\'\"\,\<\>\.\?\/\`\~\\\|\ ]+#", $_POST['password'])) {
        $error = "Your password must contain at least one letter, one number, and one special character.";
    } elseif ($mail_query) {
        $error = "There is another account created with the email address you entered in this system. Please change the email or delete the account.";
    } else {
        $_SESSION['fname'] = $firstname;
        $_SESSION['lname'] = $lastname;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['country'] = $country;
        $_SESSION['city'] = $city;
        $_SESSION['password'] = $hashedPassword;
        $_SESSION['filename'] = $filename;

        $mail = require __DIR__ . "/mail/mailer.php";
        $mail->setFrom("noreply@example.com", "ONLINE SHOES STORING MANAGEMENT SYSTEM");
        $mail->addAddress($email);
        $mail->Subject = "Account confirmation";
        $mail->Body = <<<END
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <style>
                /* Import Poppins font */
                @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');
        
                body {
                    font-family: 'Poppins', sans-serif;
                }
        
                .central-div {
                    padding: 0;
                    margin: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
        
                .container {
                    margin: 50px;
                    width: 45%;
                    margin-left: auto;
                    margin-right: auto;
                    padding: 1.25rem;
                    text-align: center;
                    border-radius: 0.375rem;
                   font-family: 'Poppins', sans-serif;
                    box-shadow: 0px 1px 10px gray;
                    border:1px solid gray;
                    background-color:white;
                }
                .btn {
                    margin: auto;
                    background-color: black;
                    padding: 0.5rem;
                    width: 35%;
                    cursor: pointer;
                    color: white;
                    opacity: 0.75;
                    margin-top: 0.75rem;
                    margin-bottom: 0.75rem;
                    border-radius: 5px;
                    font-family: 'Poppins', sans-serif;
                }
        
                .btn a {
                    color: white;
                    text-decoration: none;
                    font-family: 'Poppins', sans-serif;
                }
        
                @media screen and (max-width: 1000px) {
                    .container {
                        width: 70%;
                    }
                }
        
                @media screen and (max-width: 550px) {
                    .container {
                        width: 90%;
                    }
                }
            </style>
        </head>
        <body>
            <div class="central-div">
                <div class="container">
                    <h3>ONLINE SHOES STORING MANAGEMENT SYSTEM</h3>
                    <img class="mx-auto" src="https://www.shutterstock.com/image-vector/shoes-store-logo-icon-vector-260nw-1718722336.jpg" alt="" style="object-fit:cover" width="200px" height="180px">
                    <p>Hello $firstname $lastname,</p>
                    <p>Thank you for registering with online shoes storing management system! We are happy to have you join our community.
                    To complete the registration process, simply click on the "Confirm Account" button below:</p>
                    <p class="btn"><a href='http://localhost/ONLINESHOESTORE/controllers/confirm-account.php'>Confirm Account</a></p>
                    <hr>
                    <p style="font-style:italic;">If this registration was not done by you, please ignore this email.</p>
                </div>
            </div>
        </body>
        </html>
        END;
        
        try {
            $mail->send();
        } catch (Exception $e) {
            $error = "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
        }

        $success = "Message sent to your email, please check your inbox to confirm your account.";
    }
}
?>
