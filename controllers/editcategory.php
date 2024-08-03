<?php
    require_once('database/db.php');
    $success = null;
    $error = null;

    if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
        $category_id = $_GET['category_id'];
        $query = $db->prepare('SELECT * FROM categories WHERE category_id = ?');
        $query->execute([$category_id]);
        $categories = $query->fetch();

        if ($categories) {
            $category_id = $categories['category_id'];
            $category_name = $categories['category_name'];
            $category_desc = $categories['description'];
        } else {
            echo '<script>alert("Category ID not found.");</script>';
            echo '<script>window.location.href="admincategorie.php";</script>';
            exit; // Stop further execution if category ID is not found
        }
    } else {
        echo '<script>alert("No category ID provided.");</script>';
        echo '<script>window.location.href="admincategorie.php";</script>';
        exit; // Stop further execution if category ID is not provided
    }

    if (isset($_POST['edit'])) {
        $categorie = htmlspecialchars($_POST['categorie']);
        $description = htmlspecialchars($_POST['description']);

        if (empty($categorie) OR empty($description)) {
            $error = "Please fill all the fields";
        } else {
            // Check if the new category name already exists in the database and is not the same as the current one
            $existing_category_query = $db->prepare("SELECT * FROM categories WHERE category_name = :category_name AND category_id != :category_id");
            $existing_category_query->execute(array('category_name' => $categorie, 'category_id' => $category_id));
            $existing_category = $existing_category_query->fetch(PDO::FETCH_ASSOC);

            if ($existing_category) {
                $error = "The category <strong>" .$categorie. "</strong> you are trying to add already exists";
            } else {
                $update_query = $db->prepare('UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?');
                $update_result = $update_query->execute([$categorie, $description, $category_id]);

                if ($update_result) {
                    $success = "Category details updated successfully";
                } else {
                    $error = "Failed to update category details";
                }
            }
        }
    }
?>
