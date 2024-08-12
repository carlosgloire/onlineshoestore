<?php

require_once('database/db.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $getid = $_GET['id'];
    $recup_id = $db->prepare('SELECT * FROM small_images WHERE id = ?');
    $recup_id->execute(array($getid));
    $images = $recup_id->fetch();
    if ($recup_id->rowCount() > 0) {
        $shoe_id = $images['shoe_id']; 

        $delete_image = $db->prepare('DELETE FROM small_images WHERE id = ?');
        $delete_image->execute(array($getid));
        
        echo '<script>alert("Image successfully deleted");</script>';
        echo '<script>window.location.href="../admin/delete_small_images.php?shoe_id=' . $shoe_id . '";</script>';
        exit;
    } else {
        echo '<script>alert("No image found for this shoe");</script>';
        echo '<script>window.location.href="../admin/delete_small_images.php?shoe_id=' . $shoe_id . '";</script>';
        exit;
    }
}
?>
