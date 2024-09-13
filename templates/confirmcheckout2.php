<?php
session_start();
require_once('../controllers/database/db.php');

if (!isset($_GET['order_id'])) {
    header('Location: cart.php');
    exit();
}

$order_id = $_GET['order_id'];
$country = isset($_GET['country']) ? $_GET['country'] : '';
$address = isset($_GET['address']) ? $_GET['address'] : '';
$whatsapp = isset($_GET['whatsapp']) ? $_GET['whatsapp'] : '';
$amount = isset($_GET['amount']) ? $_GET['amount'] : 0;  // Set default value or handle gracefully

if (!$country || !$address || !$whatsapp || !$amount) {
    // Handle missing parameters gracefully, e.g., redirect to an error page or back to payment.php
    header('Location: payment.php');
    exit();
}

$payment_status = "completed"; // Set the payment status as completed

// Store payment details in the payment table
$payment_query = $db->prepare('INSERT INTO payment (order_id, payment_date, payment_method, status, amount) VALUES (?, NOW(), ?, ?, ?)');
$payment_query->execute([$order_id, 'Flutterwave', $payment_status, $amount]);

// Update order status to 'completed'
$update_order_query = $db->prepare('UPDATE orders SET status = "completed" WHERE order_id = ?');
$update_order_query->execute([$order_id]);

// Update order status to 'completed'
$update_order_query = $db->prepare('UPDATE order_user SET status = "completed" WHERE order_id = ?');
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

// Redirect to payment success page
header('Location: payment_sucess.php');

exit();
?>
