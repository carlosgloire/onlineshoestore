<?php
$success = null;
$error = null;

require_once('database/db.php');
if (isset($_POST['add'])) {
    $categorie = htmlspecialchars($_POST['categorie']);
    $description = htmlspecialchars($_POST['description']);
    if (empty($categorie) OR empty($description)) {
        $error = "Please fill all the fields";
    } 
    
    else {
        // Check if a category already exist
        $existing_category_query = $db->prepare("SELECT * FROM categories WHERE category_name=:category_name ");
        $existing_category_query->execute(array('category_name'=>$categorie));
        $existing_category = $existing_category_query->fetch(PDO::FETCH_ASSOC);
        if($existing_category){
            $error = "The category <strong>" .$categorie. "</strong> you are trying to add already exists";
        }else{
            $query = $db->prepare('INSERT INTO categories (category_name,description) VALUES (:category_name,:description)');
            $query->bindParam(':category_name', $categorie);
            $query->bindParam('description',$description);
            $query->execute();
            $success = "Category added successfuly";
        }
   
       

    }
}
?>
