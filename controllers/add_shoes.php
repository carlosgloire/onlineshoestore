
<?php
$error = null;
$success = null;
require_once('database/db.php');

if(isset($_POST['add'])){
    $name = htmlspecialchars($_POST['name']);
    $brand = htmlspecialchars($_POST['brand']);
    $color = htmlspecialchars($_POST['color']);
    $price = htmlspecialchars($_POST['price']);
    $description = htmlspecialchars($_POST['description']);
    $type = htmlspecialchars($_POST['type']);
    $category = htmlspecialchars($_POST['category']);
    $stock =htmlspecialchars($_POST['stock']);
    $filename = $_FILES["uploadfile"]["name"];
    $filesize = $_FILES["uploadfile"]["size"];
    $filetype = $_FILES["uploadfile"]["type"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "../templates/shoes/" . $filename;
    $allowed_formats = array('jpg','jpeg','png','JPG','JPEG','PNG');
    // Récupération des valeurs des cases à cocher
    $size = isset($_POST["size"]) ? $_POST["size"] : [];
    // Convertir les sizes en une seule chaîne de caractères
    $size_string = implode(', ', $size); // This is the correct way to convert the array to a string
    $existing_shoe = $db->prepare('SELECT * FROM shoes WHERE name = :name AND brand = :brand AND type = :type AND color = :color AND size = :size AND price = :price AND stock = :stock AND category_id = :category_id AND description = :description AND photo = :photo');
    $existing_shoe->execute(array('name' => $name, 'brand' => $brand, 'type' => $type, 'color' => $color, 'size' => $size_string, 'price' => $price, 'category_id' => $category, 'description' => $description,'stock' => $stock, 'photo' => $filename));
    $get_shoes = $existing_shoe->fetch();
    if(empty($name) || empty($brand) || empty($color) || empty($price) || empty($description) || empty($filename) || empty($stock)){
        $error = "Please fill all fields !!";
    } elseif($filesize > 5000000){
        $error = "Your photo should not exceed 5MB";
    } elseif($type == 'type'){
        $error = "Please select type";
    } elseif($category == 'category'){
        $error = "Please select the category";
    } elseif($get_shoes){
        $error = "The shoe <strong>" .$name. "</strong> you are trying to add already exists";
    } else {
        if(!move_uploaded_file($tempname, $folder)){
            $error = "ERROR!!";
        } else {
            $query = $db->prepare('INSERT INTO shoes (name, brand, type, color, size, price, stock, category_id, description, photo) VALUES(:name, :brand, :type, :color, :size, :price,:stock, :category_id, :description, :photo)');
            $query->bindParam(':name', $name);
            $query->bindParam(':brand', $brand);
            $query->bindParam(':type', $type);
            $query->bindParam(':color', $color);
            $query->bindParam(':price', $price);
            $query->bindParam(':stock', $stock);
            $query->bindParam(':category_id', $category);
            $query->bindParam(':description', $description);
            $query->bindParam(':photo', $filename);
            $query->bindParam(':size', $size_string, PDO::PARAM_STR); // Bind the size_string here
          
            $query->execute();
            $success = "Shoe added successfully ✅";
        }
    }
}
