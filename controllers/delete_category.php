<?php

require_once('database/db.php');

if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
    $getid = $_GET['category_id'];
    $recup_id = $db->prepare('SELECT * FROM categories WHERE category_id = ?');
    $recup_id->execute(array($getid));
    if ($recup_id->rowCount() > 0) {
        $delete_image = $db->prepare('DELETE FROM categories WHERE category_id = ?');
        $delete_image->execute(array($getid));
        echo '<script>alert("Category successfully deleted");</script>';
        echo '<script>window.location.href="../admin/admincategorie.php";</script>';
        exit;
    } else {
        echo "<script>alert('No category found');</script>";
        echo '<script>window.location.href="../admin/admincategorie.php";</script>';
        exit;
    }
}
?>
