<?php
session_start();
require_once('database/db.php');
$mailer = require("mail/mailer.php");

function notconnected(){
    if (!isset($_SESSION['user'])) {
        // Redirect to the login page if not logged in
        header("Location: ../templates/login.php");
        exit();
    }
}
notconnected();

$user_id = $_SESSION['user_id'];

// Check if user_id exists in the users table
$query = $db->prepare('SELECT * FROM users WHERE user_id = ?');
$query->execute([$user_id]);
$user = $query->fetch();

if (!$user) {
    die("User ID does not exist in the database.");
}

if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    $order_total = 0;

    // Insert a new order record
    $order_query = $db->prepare('INSERT INTO orders (user_id, order_date, status) VALUES (?, NOW(), "pending")');
    $order_query->execute([$user_id]);
    $order_id = $db->lastInsertId(); // Get the ID of the newly created order

    // Insert a new order record for user
    $order_query_user = $db->prepare('INSERT INTO order_user (order_id, user_id, order_date, status) VALUES (?, ?, NOW(), "pending")');
    $order_query_user->execute([$order_id, $user_id]); // Use the same $order_id


    // Email content initialization
    $order_details = "";

    // Insert each product in the order
    foreach ($_SESSION['panier'] as $item) {
        $product_id = $item['shoe_id'];
        $quantity = isset($item['quantity']) ? $item['quantity'] : 0;
        $colors = isset($item['colors']) ? implode(', ', $item['colors']) : '';
        $sizes = isset($item['sizes']) ? implode(', ', $item['sizes']) : '';

        // Get product price
        $product_query = $db->prepare('SELECT name, price FROM shoes WHERE shoe_id = ?');
        $product_query->execute([$product_id]);
        $product = $product_query->fetch();

        if ($product) {
            $total_price = $product['price'] * $quantity;
            $order_total += $total_price;

            // Insert the product into the order_items table
            $item_query = $db->prepare('INSERT INTO order_item (order_id, shoe_id, quantity, total_price, color, size) VALUES (?, ?, ?, ?, ?, ?)');
            $item_query->execute([$order_id, $product_id, $quantity, $total_price, $colors, $sizes]);

            // Insert the product into the order_items table for a user
            $item_query = $db->prepare('INSERT INTO order_item_user (order_id, shoe_id, quantity, total_price, color, size) VALUES (?, ?, ?, ?, ?, ?)');
            $item_query->execute([$order_id, $product_id, $quantity, $total_price, $colors, $sizes]);

            // Append product details to email content
            $order_details .= <<<END
            <div class="product">
                <p><strong>Product:</strong> {$product['name']}</p>
                <p><strong>Quantity:</strong> $quantity</p>
                <p><strong>Color:</strong> $colors</p>
                <p><strong>Size:</strong> $sizes</p>
                <p><strong>Total Price:</strong> $total_price RWF</p>
            </div>
            <hr>
END;
        }
    }

    // Update the total order amount
    $update_order_query = $db->prepare('UPDATE orders SET total_amount = ? WHERE order_id = ?');
    $update_order_query->execute([$order_total, $order_id]);

    // Update the total order user amount
    $update_order_user_query = $db->prepare('UPDATE order_user SET total_amount = ? WHERE order_id = ?');
    $update_order_user_query->execute([$order_total, $order_id]);

    // Store order ID in session to be used on the payment page
    $_SESSION['order_id'] = $order_id;

    // Send email to admin
    $admin_email = 'ndayisabarenzaho@gmail.com';
    $subject = "New Order Placed by {$user['firstname']} {$user['lastname']}";
    $email_body = <<<END
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
                padding: 20px;
            }
            .container {
                background-color: #fff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                white-space: pre-wrap; /* Preserve white space and line breaks */
            }
            .product {
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 1px solid #ddd;
            }
            hr {
                border: 1px solid #eee;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <p><strong>New Order Notification</strong></p>
            <p><strong>User:</strong> {$user['firstname']} {$user['lastname']}</p>
            <p><strong>Order Details:</strong></p>
            $order_details
            <p><strong>Total Order Amount:</strong>$order_total RWF</p>
        </div>
    </body>
    </html>
END;

    $mailer->setFrom('noreply@yourdomain.com', 'ONLINE SHOES STORING MANAGEMENT SYSTEM'); // Set a no-reply sender
    $mailer->addReplyTo('noreply@yourdomain.com', 'No Reply'); // Add a no-reply address
    $mailer->Subject = html_entity_decode($subject); // Decode HTML entities in subject
    $mailer->CharSet = 'UTF-8'; // Set charset to UTF-8
    $mailer->Body = $email_body;
    $mailer->isHTML(true);

    // Set the recipient to the admin email
    $mailer->addAddress($admin_email, 'Admin'); // Admin's email address

    try {
        $mailer->send();
        echo "Order notification email sent successfully.\n";
    } catch (Exception $e) {
        echo "Failed to send order notification email.\n";
    }

    // Redirect to the payment page
    header('Location: ../templates/payment.php');
    exit();
} else {
    // Redirect to the cart page if the cart is empty
    header('Location: ../templates/cart.php');
    exit();
}
?>
