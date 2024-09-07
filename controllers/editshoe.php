<?php
$error = null;
$success = null;
require_once('../controllers/database/db.php'); // Adjust path as needed

$name = $brand = $size = $color = $price = $description = $photo = $shoe_type = $shoe_category = '';

// Fetch shoe details if shoe_id is provided
if (isset($_GET['shoe_id']) && !empty($_GET['shoe_id'])) {
    $shoe_id = $_GET['shoe_id'];
    $query = $db->prepare('SELECT * FROM shoes WHERE shoe_id = ?');
    $query->execute([$shoe_id]);
    $shoe = $query->fetch();
    if ($shoe) {
        $name = $shoe['name'];
        $brand = $shoe['brand'];
        $size = $shoe['size'];
        $color = $shoe['color'];
        $price = $shoe['price'];
        $description = $shoe['description'];
        $photo = $shoe['photo'];
        $shoe_type = $shoe['type'];
        $stock = $shoe['stock'];
        $shoe_category = $shoe['category_id'];
    } else {
        echo '<script>alert("Shoe ID not found.");</script>';
        echo '<script>window.location.href="index.php";</script>';
        exit; // Stop further execution if shoe ID is not found
    }
} else {
    echo '<script>alert("No shoe ID provided.");</script>';
    echo '<script>window.location.href="index.php";</script>';
    exit; // Stop further execution if shoe ID is not provided
}

// Process form submission
if (isset($_POST['edit'])) {
    $shoe_name = htmlspecialchars($_POST['name']);
    $shoe_brand = htmlspecialchars($_POST['brand']);
    $shoe_color = htmlspecialchars($_POST['color']);
    $shoe_size = htmlspecialchars($_POST['size']);
    $shoe_price = htmlspecialchars($_POST['price']);
    $shoe_stock = htmlspecialchars($_POST['stock']);
    $shoe_description = htmlspecialchars($_POST['description']);
    $new_shoe_type = htmlspecialchars($_POST['type']);
    $new_shoe_category = htmlspecialchars($_POST['category']);
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "../templates/shoes/" . $filename;
    $allowed_formats = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG');

    // Validation and error checking
    if (empty($shoe_name) || empty($shoe_brand) || empty($shoe_color) || empty($shoe_size) || empty($shoe_price) || empty($shoe_description)) {
        $error = "Please fill all fields!";
    } elseif ($_FILES["uploadfile"]["size"] > 5000000) {
        $error = "Your photo should not exceed 5MB";
    } else {
        // Check if the new shoe name already exists in the database and is not the same as the current one
        $existing_shoe_query = $db->prepare("SELECT * FROM shoes WHERE name = :name AND shoe_id != :shoe_id");
        $existing_shoe_query->execute(array('name' => $shoe_name, 'shoe_id' => $shoe_id));
        $existing_shoe = $existing_shoe_query->fetch(PDO::FETCH_ASSOC);

        if ($existing_shoe) {
            $error = "The shoe <strong>" .$shoe_name. "</strong> you are trying to add already exists";
        } else {
            // Use the previous photo if no new photo is uploaded
            if (empty($filename)) {
                $filename = $photo;
            } else {
                // Move uploaded file to destination
                if (!move_uploaded_file($tempname, $folder)) {
                    $error = "Error uploading file";
                }
            }

            // Use the previous type if no new type is selected
            if ($new_shoe_type == 'type') {
                $new_shoe_type = $shoe_type;
            }

            // Use the previous category if no new category is selected
            if ($new_shoe_category == 'category') {
                $new_shoe_category = $shoe_category;
            }

            // Update shoe details in the database
            $update_query = $db->prepare('UPDATE shoes SET name=?, brand=?, color=?, price=?,stock=?, description=?, type=?, category_id=?, photo=?, size=? WHERE shoe_id=?');
            $update_result = $update_query->execute([$shoe_name, $shoe_brand, $shoe_color, $shoe_price,$shoe_stock, $shoe_description, $new_shoe_type, $new_shoe_category, $filename, $shoe_size, $shoe_id]);

            if ($update_result) {
                echo "<script>alert('Shoe details updated successfully');</script>";
                echo '<script>window.location.href="../admin/shoes.php";</script>';
                exit;
                $success = "Shoe details updated successfully";
            } else {
                echo "<script>alert('Failed to update shoe details');</script>";
                echo '<script>window.location.href="../admin/shoes.php";</script>';
                exit;
            }
        }
    }
}
?>
