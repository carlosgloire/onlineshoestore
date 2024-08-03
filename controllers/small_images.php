<?php
$success = null;
$error = null;
require_once('database/db.php');
// Get the shoe id
if (isset($_GET['shoe_id']) && !empty($_GET['shoe_id'])) {
    $id = $_GET['shoe_id'];
    $retrieveShoeId = $db->prepare('SELECT * FROM shoes WHERE shoe_id = ?');
    $retrieveShoeId->execute(array($id));
    $infos = $retrieveShoeId->fetch();
    if($infos){
        $id_fetched= $infos['shoe_id'];
        $name=$infos['name'];
    }
    else{
        echo '<script>alert("No product found");</script>';
        echo '<script>window.location.href="../produit.php";</script>';
        exit;
    }
}
if (isset($_POST['add'])) {
    $filename = $_FILES["uploadfile"]["name"];
    $filesize = $_FILES["uploadfile"]["size"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "../templates/small_images/" . $filename;
    $allowedExtensions = ['png', 'jpg', 'jpeg'];
    $pattern = '/\.(' . implode('|', $allowedExtensions) . ')$/i';

    if (empty($filename)) {
        $error = "Please choose an image"; 
    } elseif (!preg_match($pattern, $_FILES['uploadfile']['name']) && !empty($_FILES['uploadfile']['name'])) {
        $error = "Your file must be in \"jpg, jpeg or png\" format";
    } elseif ($filesize > 1000000) {
        $error = "Your file should not exceed 1Mb";
    } else {
        // Check if a product already exists
        $existing_product_query = $db->prepare("SELECT * FROM small_images WHERE shoe_image=:shoe_image AND shoe_id=:shoe_id ");
        $existing_product_query->execute(array('shoe_image' => $filename, 'shoe_id' => $id));
        $existing_product = $existing_product_query->fetch(PDO::FETCH_ASSOC);
        
        if ($existing_product) {
            $error = "This image already exists";
        } else {
            // Insert into image table
            $query = $db->prepare('INSERT INTO small_images (shoe_image, shoe_id) VALUES (:shoe_image, :shoe_id)');
            $query->bindParam(':shoe_image', $filename);
            $query->bindParam(':shoe_id', $id);
            if ($query->execute()) {
                // Move the uploaded file to the target folder
                if (move_uploaded_file($tempname, $folder)) {
                    $success = "Image successfully added";
                } else {
                    $error = "Failed to upload image";
                }
            } else {
                $error = "Error adding the product";
            }
        }
    }
}
?>
