<?php
require_once('../../controllers/database/db.php');

// Get form data
$product_id = $_POST['shoe_id'];
$rating = $_POST['rating'];

// Insert review into the database
$sql = 'INSERT INTO reviews (shoe_id, rating) VALUES (?, ?)';
$stmt = $db->prepare($sql);
$stmt->execute([$product_id, $rating]);
echo '<script>alert("Your review has been added successfully");</script>';
echo '<script>window.location.href="../index.php";</script>';
exit;
?>
