<?php
session_start();
require_once('../controllers/functions.php');
require_once('../controllers/database/db.php');

notconnected();

// Fetch all orders, order items, and user details from the database
$query = $db->prepare("
    SELECT 
        o.order_id,
        o.order_date,
        o.status as order_status,
        u.firstname,
        u.lastname,
        u.country,
        u.city,
        u.phone,
        s.photo,
        s.price,
        oi.size, 
        oi.color, 
        oi.order_item_id,  
        oi.quantity,  
        oi.total_price
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    JOIN order_item oi ON o.order_id = oi.order_id
    JOIN shoes s ON oi.shoe_id = s.shoe_id
    ORDER BY o.order_date DESC
");
$query->execute();
$orders = $query->fetchAll(PDO::FETCH_ASSOC);

// Group orders by firstname, lastname, order_id, and date
$grouped_orders = [];

foreach ($orders as $order) {
    $firstname = $order['firstname'];
    $lastname = $order['lastname'];
    $country = $order['country'];
    $city = $order['city'];
    $phone = $order['phone'];
    $order_id = $order['order_id'];
    $date = date('d/m/Y', strtotime($order['order_date']));
    
    if (!isset($grouped_orders[$firstname])) {
        $grouped_orders[$firstname] = [];
    }
    if (!isset($grouped_orders[$firstname][$lastname])) {
        $grouped_orders[$firstname][$lastname] = [
            'country' => $country,
            'city' => $city,
            'phone' => $phone,
            'dates' => []
        ];
    }
    if (!isset($grouped_orders[$firstname][$lastname]['dates'][$date])) {
        $grouped_orders[$firstname][$lastname]['dates'][$date] = [];
    }
    if (!isset($grouped_orders[$firstname][$lastname]['dates'][$date][$order_id])) {
        $grouped_orders[$firstname][$lastname]['dates'][$date][$order_id] = [];
    }
    $grouped_orders[$firstname][$lastname]['dates'][$date][$order_id][] = $order;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <!--css-->
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/admin.css">
    <link rel="stylesheet" href="../asset/css/product.css">

    <!--Font family-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!--Icons-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.0/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
    <!-- Admin header -->
    <header class="header-admin">
        <div>
            <h2>Dashboard</h2>
        </div>
        <div class="admin-menu">
            <i class="bi bi-list"></i>
            <i class="bi bi-x"></i>
        </div>
    </header>

    <section class="admin-section">
        <div class="first-bloc">
            <nav>
                <!-- Navigation links -->
                <a href="admin.php"><i class="bi bi-clipboard-pulse"></i><span>Dashboard</span></a>
                <a href="admincategorie.php"><i class="bi bi-bookmark-star"></i><span>Categories</span></a>
                <a href="shoes.php"><i class="fa-solid fa-socks"></i><span>Shoes</span></a>
                <a href="newsletter.php"><i class="bi bi-card-text"></i><span>Newsletter</span></a>
                <a href="slide.php"><i class="bi bi-file-image"></i><span>Slides</span></a>
                <a class="activ" href="#"><i class="bi bi-border"></i><span>Orders</span></a>
                <a href="shipment.html"><i class="bi bi-flower2"></i><span>Shipment</span></a>
            </nav>
        </div>
        <div class="right-side" style="margin-bottom: 30px;">
            <h2 style="text-align: center;margin-top:20px">Orders</h2>
            <?php foreach ($grouped_orders as $firstname => $orders_by_firstname): ?>
                <?php foreach ($orders_by_firstname as $lastname => $user_details): ?>
                    <h4 style="margin-left: 30px;"><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></h4>
                    <p style="margin-left: 30px; color: #555;"><?php echo htmlspecialchars($user_details['city'] . ', ' . $user_details['country'] . ' - ' . $user_details['phone']); ?></p>
                    <?php foreach ($user_details['dates'] as $date => $orders_by_date): ?>
                        <div class="date-group">
                            <div class="date" style="display: flex;color: #9a9a9a;font-weight: 500;font-size: 0.8rem;margin-top: 20px;justify-content: flex-end;margin-right:30px"><?php echo $date; ?></div>
                            <?php foreach ($orders_by_date as $order_id => $items): 
                                $total_order_price = 0;
                                $pending_order_found = false;
                                foreach ($items as $item):
                                    $total_order_price += $item['total_price'];
                                    if ($item['order_status'] == 'pending') {
                                        $pending_order_found = true;
                                    }
                                ?>
                                <div class="our-panier-prod">
                                    <div class="order-prod">
                                        <div>
                                            <p><img src="../templates/shoes/<?=$item['photo']?>" alt=""></p>
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
                                            <span><?php echo htmlspecialchars($item['price']); ?> RWF</span>
                                        </div>
                                    </div>
                                    <div class="price">
                                        <h4>Total price</h4>
                                        <span><?php echo htmlspecialchars($item['total_price']); ?> RWF</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <p style="text-align: right;margin-right: 30px;margin-top:20px;font-weight:600">Total order price: <?= htmlspecialchars($total_order_price) ?> RWF</p>
                    
                            <p style="text-align: right;margin-right: 30px;">
                                <?php 
                                    switch ($items[0]['order_status']) {
                                        case 'pending':
                                            echo "Payment status: Pending </span>";
                                            break;
                                        case 'completed':
                                            echo "Payment status: Completed <span style='color:green'>✔</span>";
                                            break;
                                        case 'cancelled':
                                            echo "Payment status: Cancelled ";
                                            break;
                                    }
                                ?>
                            </p>
                          
                        <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
