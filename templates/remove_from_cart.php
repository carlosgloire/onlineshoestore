<?php
session_start();

if (isset($_POST['shoe_id'])) {
    $product_id = $_POST['shoe_id'];

    // Find and remove the product from the cart
    foreach ($_SESSION['panier'] as $key => $item) {
        if ($item['shoe_id'] == $product_id) {
            unset($_SESSION['panier'][$key]);
            break;
        }
    }

    // Re-index the array to avoid issues with numeric keys
    $_SESSION['panier'] = array_values($_SESSION['panier']);
}

header('Location: cart.php');
exit();
?>
