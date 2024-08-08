<?php
session_start();
require_once('../database/db.php');

if (!isset($_SESSION['order_id'])) {
    // Redirect to cart if no order ID is set
    header('Location: panier.php');
    exit();
}

// Retrieve order details
$order_id = $_SESSION['order_id'];

// Update order status to 'completed'
$update_order_query = $db->prepare('UPDATE orders SET status = "completed" WHERE order_id = ?');
$update_order_query->execute([$order_id]);

// Retrieve all items in the order
$order_items_query = $db->prepare('SELECT shoe_id, quantity FROM order_item WHERE order_id = ?');
$order_items_query->execute([$order_id]);
$order_items = $order_items_query->fetchAll(PDO::FETCH_ASSOC);

// Update the stock for each shoe
foreach ($order_items as $item) {
    $shoe_id = $item['shoe_id'];
    $quantity = $item['quantity'];

    // Subtract the quantity from the stock
    $update_stock_query = $db->prepare('UPDATE shoes SET stock = stock - ? WHERE shoe_id = ?');
    $update_stock_query->execute([$quantity, $shoe_id]);
}

// Clear the session order ID
unset($_SESSION['order_id']);

// Clear the cart after successful payment
unset($_SESSION['panier']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
    <link rel="stylesheet" href="../css/success.css"> <!-- Optional: Add your CSS here -->
</head>
<body>
    <div class="success-container">
        <h1>Thank you for your order!</h1>
        <p>Your payment has been processed successfully.</p>
        <a href="../index.php" class="continue-shopping">Continue Shopping</a>
    </div>
</body>
</html>
