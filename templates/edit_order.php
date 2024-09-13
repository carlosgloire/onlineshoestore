<?php
session_start();
require_once('../controllers/functions.php');
require_once('../controllers/database/db.php');

notconnected();

if (!isset($_GET['order_item_id']) || empty($_GET['order_item_id'])) {
    echo '<script>alert("No order item ID provided.");</script>';
    echo '<script>window.location.href="dashboard.php";</script>';
    exit;
}
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    echo '<script>alert("No order item ID provided.");</script>';
    echo '<script>window.location.href="dashboard.php";</script>';
    exit;
}

$order_item_id = $_GET['order_item_id'];
$order_id = $_GET['order_id'];
// Fetch the order item details
$query = $db->prepare("SELECT oi.*, s.name AS shoe_name, s.photo, s.price,s.shoe_id
                       FROM order_item_user oi 
                       JOIN shoes s ON oi.shoe_id = s.shoe_id 
                       WHERE oi.order_item_id = ?");
$query->execute([$order_item_id]);
$order_item = $query->fetch(PDO::FETCH_ASSOC);


if (!$order_item) {
    echo '<script>alert("Order item not found.");</script>';
    echo '<script>window.location.href="dashboard.php";</script>';
    exit;
}

// Fetch all shoes for the dropdown
$shoes_query = $db->prepare("SELECT * FROM shoes");
$shoes_query->execute();
$shoes = $shoes_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch available sizes and colors for the selected shoe
$shoe_id = $order_item['shoe_id'];
$sizes_query = $db->prepare("SELECT size FROM shoes WHERE shoe_id = ?");
$sizes_query->execute([$shoe_id]);
$sizes = $sizes_query->fetchAll(PDO::FETCH_ASSOC);

$colors_query = $db->prepare("SELECT color FROM shoes WHERE shoe_id = ?");
$colors_query->execute([$shoe_id]);
$colors = $colors_query->fetchAll(PDO::FETCH_ASSOC);

$available_sizes = implode(', ', array_column($sizes, 'size'));
$available_colors = implode(', ', array_column($colors, 'color'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shoe_id = $_POST['shoe_id'];
    $quantity = $_POST['quantity'];
    $sizes = isset($_POST['sizes']) ? $_POST['sizes'] : $order_item['size'];
    $colors = isset($_POST['colors']) ? $_POST['colors'] : $order_item['color'];

    // Fetch the available stock of the selected shoe
    $stock_query = $db->prepare("SELECT stock FROM shoes WHERE shoe_id = ?");
    $stock_query->execute([$shoe_id]);
    $available_stock = $stock_query->fetchColumn();

    if ($quantity > $available_stock) {
        echo '<script>alert("The quantity entered is unavailable. Available stock: ' . htmlspecialchars($available_stock) . '");</script>';
        echo '<script>window.location.href="edit_order.php?order_item_id=' . htmlspecialchars($order_item_id) . '&order_id=' . htmlspecialchars($order_id) . '";</script>';
        exit;
    }

    // Fetch the price of the selected shoe
    $price_query = $db->prepare("SELECT price FROM shoes WHERE shoe_id = ?");
    $price_query->execute([$shoe_id]);
    $price = $price_query->fetchColumn();

    // Calculate the total price
    $total_price = $price * $quantity;

    // Update order_item and order_item_user
    $update_query = $db->prepare("UPDATE order_item SET shoe_id = ?, quantity = ?, size = ?, color = ?, total_price = ? WHERE order_id = ?");
    $update_query->execute([$shoe_id, $quantity, $sizes, $colors, $total_price, $order_id]);

    $update_query = $db->prepare("UPDATE order_item_user SET shoe_id = ?, quantity = ?, size = ?, color = ?, total_price = ? WHERE order_item_id = ?");
    $update_query->execute([$shoe_id, $quantity, $sizes, $colors, $total_price, $order_item_id]);

    echo '<script>alert("Order item updated successfully.");</script>';
    echo '<script>window.location.href="dashboard.php";</script>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order Item</title>
    <link rel="stylesheet" href="../asset/css/style.css">
     <!--css-->
     <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">

    <!--Font family-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!--Icons-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.0/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"
    />
</head>

<body>
    <section class="login-section">
        <div class="login">
            <h2>Edit Order Item</h2>
            <form method="POST">
                <div class="form-group">
                    <label style="margin-left: 20px;" for="shoe_id">Shoe Name:</label>
                    <select name="shoe_id" id="shoe_id" class="all-inputs">
                            <option value="<?= $order_item['shoe_id'] ?>">
                                <?= htmlspecialchars($order_item['shoe_name']) ?>
                            </option>
                        <?php foreach ($shoes as $shoe): ?>
                            <?= $shoe['shoe_id'] != $order_item['shoe_id']? '<option value='.$shoe['shoe_id'].'>'.$shoe['name'].'</option>':''?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="all-inputs">
                    <label  for="quantity">Quantity:</label>
                    <input style="width:100%" type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($order_item['quantity']) ?>" required>
                </div>
                <div class="form-group">
                    <label style="margin-left: 20px;">Available Sizes: <?= htmlspecialchars($available_sizes) ?></label>
                    <div class="all-inputs">
                        <input style="width:100%" type="text" name="sizes" id="sizes" value="<?= htmlspecialchars($order_item['size']) ?>" placeholder="e.g. 38, 39, 40" required>
                    </div>
                </div>
                <div class="form-group">
                    <label style="margin-left: 20px;">Available Colors: <?= htmlspecialchars($available_colors) ?></label>
                    <div class="all-inputs">
                        <input style="width:100%" type="text" name="colors" id="colors" value="<?= htmlspecialchars($order_item['color']) ?>" placeholder="e.g. white, black" required>
                    </div>
                    
                </div>
                <div class="submit">
                    <input type="submit" name="edit" value="Updtate">
                </div>
            </form>
        </div>
    </section>
</body>
</html>
