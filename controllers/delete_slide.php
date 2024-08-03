<?php

require_once('database/db.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $getid = $_GET['id'];
    $recup_id = $db->prepare('SELECT * FROM slides WHERE id = ?');
    $recup_id->execute(array($getid));
    if ($recup_id->rowCount() > 0) {
        $delete_image = $db->prepare('DELETE FROM slides WHERE id = ?');
        $delete_image->execute(array($getid));
        echo '<script>alert("Slide successfully deleted");</script>';
        echo '<script>window.location.href="../admin/slide.php";</script>';
        exit;
    } else {
        echo "<script>alert('No slide found');</script>";
        echo '<script>window.location.href="../admin/slide.php";</script>';
        exit;
    }
}
?>
