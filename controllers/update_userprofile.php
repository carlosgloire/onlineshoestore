
<?php
require_once('database/db.php');
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $_SESSION['user_id'] = $user_id; // Ensure session user_id is set
    $query = $db->prepare('SELECT * FROM users WHERE user_id = ?');
    $query->execute([$user_id]);
    $user = $query->fetch();

    if ($user) {
        $photo = $user['photo'];
        $fname = $user['firstname'];
        $lname = $user['lastname'];
        $email = $user['email'];
        $phone = $user['phone'];
        $country_fetched = $user['country'];
        $city = $user['city'];
    } else {
        echo '<script>alert("User ID not found.");</script>';
        echo '<script>window.location.href="templates/";</script>';
        exit;
    }
} else {
    echo '<script>alert("No user ID provided.");</script>';
    echo '<script>window.location.href="templates/";</script>';
    exit;
}

if (isset($_POST['edit'])) {
 
    $firstname = htmlspecialchars($_POST['fname']);
    $lastname = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $country = htmlspecialchars($_POST['country']);
    $city = htmlspecialchars($_POST['city']);
    $filename = $_FILES["uploadfile"]["name"];
    $filesize = $_FILES["uploadfile"]["size"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "../templates/profile_photo/" . $filename;
    $allowedExtensions = ['png', 'jpg', 'jpeg'];
    $pattern = '/\.(' . implode('|', $allowedExtensions) . ')$/i';

    $existing_user_query = $db->prepare("SELECT * FROM users WHERE email = :email AND user_id != :user_id");
    $existing_user_query->execute(array('email' => $email, 'user_id' => $_SESSION['user_id']));
    $existing_user = $existing_user_query->fetch(PDO::FETCH_ASSOC);

    if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($country)) {
        echo '<script>alert("Please complete all fields.");</script>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Your email is incorrect.");</script>';
    } elseif (!preg_match("#^[+]+[0-9]{12}$#", $_POST['phone'])) {
        echo '<script>alert("Please write the phone number with the country code Ex:+1 000 000 000.");</script>';
    } elseif ($country == 'select') {
        $country = $country_fetched;
    } elseif (!preg_match($pattern, $_FILES['uploadfile']['name']) && !empty($_FILES['uploadfile']['name'])) {
        echo '<script>alert("Your file must be in \"jpg, jpeg or png\" format");</script>';
    } elseif ($filesize > 3000000) {
        echo '<script>alert("Your file must not exceed 3Mb");</script>';
    } elseif (!empty($filename) && !move_uploaded_file($tempname, $folder)) {
        echo '<script>alert("Error while uploading");</script>';
    } elseif ($existing_user) {
        echo '<script>alert("There is another account created with the email address you entered in this system. Please change the email or delete the account.");</script>';
    } else {
        if (empty($filename)) {
            $filename = $photo;
        }

        $query = $db->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ?, country = ?, city = ?, photo = ? WHERE user_id = ?");
        $update = $query->execute(array($firstname, $lastname, $email, $phone, $country, $city, $filename, $_SESSION['user_id']));

        if ($update) {
            echo '<script>alert("Profile updated successfully.");</script>';
        } else {
            echo '<script>alert("Error updating profile.");</script>';
        }
    }
}
?>