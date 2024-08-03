<?php
require_once('../database/db.php');

// Get form data
$product_id = $_POST['product_id'];
$rating = $_POST['rating'];

// Insert review into the database
$sql = 'INSERT INTO reviews_produits (product_id, rating) VALUES (?, ?)';
$stmt = $db->prepare($sql);
$stmt->execute([$product_id, $rating]);
echo '<script>alert("Votre avis a été ajouté");</script>';
echo '<script>window.location.href="../index.php";</script>';
exit;
?>
