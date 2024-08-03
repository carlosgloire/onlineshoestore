<?php
$success = null;
$error = null;
require_once('database/db.php');

if (isset($_POST['add'])) {
    $filename = $_FILES["uploadfile"]["name"];
    $filesize = $_FILES["uploadfile"]["size"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "../templates/slides_images/" . $filename;
    $allowedExtensions = ['png', 'jpg', 'jpeg'];
    $pattern = '/\.(' . implode('|', $allowedExtensions) . ')$/i';

    if (empty($filename)) {
        $error = "Please choose an image"; 
    } elseif (!preg_match($pattern, $_FILES['uploadfile']['name']) && !empty($_FILES['uploadfile']['name'])) {
        $error = "Your file must be in \"jpg, jpeg or png\" format";
    } elseif ($filesize > 2000000) {
        $error = "Your file must not exceed 2Mb";
    } else {
        // Check if an image already exists
        $existing_product_query = $db->prepare("SELECT * FROM slides WHERE photo=:photo");
        $existing_product_query->execute(array('photo' => $filename));
        $existing_product = $existing_product_query->fetch(PDO::FETCH_ASSOC);
        
        if ($existing_product) {
            $error = "This image already exists";
        } else {
            $query = $db->prepare('INSERT INTO slides (photo) VALUES (:photo)');
            $query->bindParam(':photo', $filename);
            if ($query->execute()) {
                // Move the uploaded file to the target folder
                if (move_uploaded_file($tempname, $folder)) {
                    $success = "Image added successfully";
                } else {
                    $error = "Failed to upload the image";
                }
            } else {
                $error = "Error adding the product";
            }
        }
    }
}
?>
