<?php
require_once('mail/database.php');

if(isset($_POST['send-NewsLetter'])){
    $email = $_POST['mail-newsLetter'];
    if(empty($_POST['mail-newsLetter'])){
        echo '<script>alert("Please complete your email");</script>';
        echo '<script>window.location.href="../templates/";</script>';
        exit;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Your email is incorrect");</script>';
        echo '<script>window.location.href="../templates/";</script>';
        exit;
    }else {
        $req = $mysqli->prepare('SELECT * FROM mailNewsletter WHERE email = ?');
        $req->bind_param('s', $email);
        $req->execute();
        $result = $req->get_result();

        if ($result->num_rows > 0) {
            echo '<script>alert("This email already exists");</script>';
            echo '<script>window.location.href="../templates/";</script>';
            exit;
        } else {
            $query = $mysqli->prepare('INSERT INTO mailNewsletter (email) VALUES (?)');
            $query->bind_param('s', $email);
            $query->execute();
            echo '<script>alert("Your email has been registered to receive information about our products");</script>';
            echo '<script>window.location.href="../templates/";</script>';
            exit;
        }
    }
}
?>
