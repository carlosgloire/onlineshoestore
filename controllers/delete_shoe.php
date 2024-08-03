<?php

require_once('database/db.php');

if (isset($_GET['shoe_id']) && !empty($_GET['shoe_id'])) {
    $getid = $_GET['shoe_id'];
    $recup_id = $db->prepare('SELECT * FROM shoes WHERE shoe_id = ?');
    $recup_id->execute(array($getid));
    if ($recup_id->rowCount() > 0) {
        $delete_image = $db->prepare('DELETE FROM shoes WHERE shoe_id = ?');
        $delete_image->execute(array($getid));
        echo '<script>alert("Product successfully deleted");</script>';
        echo '<script>window.location.href="../admin/shoes.php";</script>';
        exit;
    } else {
        echo "<script>alert('No product found');</script>";
        echo '<script>window.location.href="../admin/shoes.php";</script>';
        exit;
    }
}
?>
