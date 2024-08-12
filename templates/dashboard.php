<?php
session_start();
require_once('../controllers/functions.php');
require_once('../controllers/database/db.php');

notconnected();

// Fetch orders, order items, and shoe names from the database
$user_id = $_SESSION['user_id'];
$query = $db->prepare("
    SELECT 
        o.order_id,
        o.order_date,
        o.status as order_status,
        s.name,
        s.photo,
        s.price,
        oi.size, 
        oi.color, 
        oi.order_item_id,  
        oi.quantity,  
        oi.total_price,
        sh.shipment_country as shipping_country,
        sh.address as shipping_address,
        sh.whatsapp_number,
        sh.amount
    FROM order_user o
    JOIN order_item_user oi ON o.order_id = oi.order_id
    JOIN shoes s ON oi.shoe_id = s.shoe_id
    LEFT JOIN shipment sh ON o.order_id = sh.order_id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC
");
$query->execute([$user_id]);
$orders = $query->fetchAll(PDO::FETCH_ASSOC);

// Group orders by order_id and date
$grouped_orders = [];

foreach ($orders as $order) {
    $order_id = $order['order_id'];
    $date = date('d/m/Y', strtotime($order['order_date']));
    if (!isset($grouped_orders[$date])) {
        $grouped_orders[$date] = [];
    }
    if (!isset($grouped_orders[$date][$order_id])) {
        $grouped_orders[$date][$order_id] = [];
    }
    $grouped_orders[$date][$order_id][] = $order;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!--css-->
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">
    <link rel="stylesheet" href="../asset/css/admin.css">
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

    <section class="user-dashboard">
        <div class="dashboard" >
            <div class="left-side">
                <nav>
                    <a class="act" href="#">
                        <i class="bi bi-clipboard-pulse"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="profil.php?user_id=<?=$_SESSION['user_id']?>">
                        <i class="bi bi-person-check"></i>
                        <span>My profile</span>
                    </a>
                    <a href="#">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>Payment</span>
                    </a>
                    <a href="#">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Log out</span>
                    </a>
                </nav>
            </div>
        </div>

        <div class="right-side" style="margin-bottom: 30px;">
            <h2 style="text-align: center;margin-top:20px">My orders</h2>
            <?php foreach ($grouped_orders as $date => $orders_by_date): ?>
                <div class="date-group">
                    <div class="date" style="display: flex;color: #9a9a9a;font-weight: 500;font-size: 0.8rem;margin-top: 20px;justify-content: flex-end;margin-right:30px"><?php echo $date; ?></div>
                    <?php foreach ($orders_by_date as $order_id => $items): 
                        $total_order_price = 0;
                        $pending_order_found = false;
                        $shipment_country = $items[0]['shipping_country'] ?? '';
                        $shipping_address = $items[0]['shipping_address'] ?? '';
                        $whatsapp_number = $items[0]['whatsapp_number'] ?? '';
                        $amt = $items[0]['amount'] ?? '';
                        foreach ($items as $item):
                            $total_order_price += $item['total_price'];
                            if ($item['order_status'] == 'pending') {
                                $pending_order_found = true;
                            }
                        ?>
                        <div class="our-panier-prod">
                            <div class="order-prod">
                                <div>
                                    <p><img src="shoes/<?=$item['photo']?>" alt=""></p>
                                </div>
                                <div>
                                    <h4>Shoe name</h4>
                                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                                </div>
                                <div>
                                    <h4>Sizes selected</h4>
                                    <span><?php echo htmlspecialchars($item['size']); ?></span>
                                </div>
                                <div>
                                    <h4>Colors selected</h4>
                                    <span><?php echo htmlspecialchars($item['color']); ?></span>
                                </div>
                                <div>
                                    <h4>Quantity </h4>
                                    <input type="number" value="<?php echo htmlspecialchars($item['quantity']); ?>" disabled>
                                </div>
                                <div>
                                    <h4>Price </h4>
                                    <span><?php echo htmlspecialchars($item['price']); ?></span>
                                </div>
                                <div class="delete" style="display: grid;gap:10px">
                                    <a href="edit_order.php?order_item_id=<?= $item['order_item_id'] ?>&order_id=<?= $order_id ?>" style="color: black;">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="#" class="delete delete_item" gallery_id="<?= $item['order_item_id'] ?>">
                                        <i class="bi bi-trash3"></i> delete
                                    </a>
                                </div>
                                <?=popup_order_item()?>
                            </div>

                            <div class="price">
                                <h4>Total price</h4>
                                <span><?php echo htmlspecialchars($item['total_price']); ?> RWF</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <p style="text-align: right;margin-right: 30px;margin-top:20px;font-weight:600">Total order price: <?= htmlspecialchars($total_order_price) ?> RWF</p>
                    <?php if ($shipment_country || $shipping_address || $whatsapp_number): ?>
                        <p style="text-align: right;margin-right: 30px;font-weight:600">Shipped at: <?= htmlspecialchars($shipment_country) ?>, <?= htmlspecialchars($shipping_address) ?></p>
                        <p style="text-align: right;margin-right: 30px;">WhatsApp: <?= htmlspecialchars($whatsapp_number) ?></p>
                        <p style="text-align: right;margin-right: 30px;">Total order amount shipment included: <?= htmlspecialchars($amt)?> RWF</p>
                    <?php endif; ?>
                    <?php if ($pending_order_found): ?>
                        <a href="payment_order.php?order_id=<?= $order_id ?>" class="pay">Pay now</a>
                    <?php else: ?>
                        <p style="text-align: right;margin-right: 30px;">
                            <?php 
                                switch ($items[0]['order_status']) {
                                    case 'completed':
                                        echo "Payment completed <span style='color:green'>✔</span>";
                                        break;
                                    case 'cancelled':
                                        echo "Payment failed ❌";
                                        break;
                                }
                            ?>
                        </p>
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <script src="../asset/javascript/prod.js"></script>
    <script src="../asset/javascript/popup_delete_oderItem.js"></script>

</body>

</html>
