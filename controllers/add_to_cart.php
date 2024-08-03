<?php
session_start();
require_once('../controllers/database/db.php');
if (isset($_POST['ajouter_panier'])) {
    $shoe_id = $_POST['shoe_id'];
    $sizes = isset($_POST['selected_sizes']) ? json_decode($_POST['selected_sizes'], true) : [];
    $colors = isset($_POST['selected_colors']) ? json_decode($_POST['selected_colors'], true) : [];

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    if (isset($_SESSION['panier'][$shoe_id])) {
        $_SESSION['panier'][$shoe_id]['sizes'] = array_unique(array_merge($_SESSION['panier'][$shoe_id]['sizes'], $sizes));
        $_SESSION['panier'][$shoe_id]['colors'] = array_unique(array_merge($_SESSION['panier'][$shoe_id]['colors'], $colors));
    } else {
        $_SESSION['panier'][$shoe_id] = [
            'shoe_id' => $shoe_id,
            'sizes' => $sizes,
            'colors' => $colors,
            'quantity' => 1
        ];
    }

    echo '<script>alert("Product added to cart successfully!");</script>';
    echo '<script>window.location.href="../templates/cart.php";</script>';
} else {
    echo '<script>alert("No data received");</script>';
    echo '<script>window.location.href="../templates/index.php";</script>';
}


?>
