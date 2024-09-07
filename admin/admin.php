<?php
session_start();
$error = null;
$success = null;
require_once('../controllers/database/db.php');
require_once('../controllers/functions.php');
notAdmin();

// Fetch the count of category_id and sum of stock from the joined tables
$query = $db->prepare('
    SELECT 
        c.category_id,
        c.category_name,
        COUNT(s.shoe_id) AS total_shoes,
        SUM(s.stock) AS total_stock
    FROM 
        categories c
    LEFT JOIN 
        shoes s ON c.category_id = s.category_id
    GROUP BY 
        c.category_id, c.category_name
');
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetch the sum of all shoes in stock
$totalStockQuery = $db->prepare('SELECT SUM(stock) AS total_stock FROM shoes');
$totalStockQuery->execute();
$totalStockResult = $totalStockQuery->fetch(PDO::FETCH_ASSOC);
$totalStock = $totalStockResult['total_stock'];

// Fetch the total number of users
$totalUsersQuery = $db->prepare('SELECT COUNT(user_id) AS total_users FROM users');
$totalUsersQuery->execute();
$totalUsersResult = $totalUsersQuery->fetch(PDO::FETCH_ASSOC);
$totalUsers = $totalUsersResult['total_users'];

// Fetch the total number of orders
$totalOrdersQuery = $db->prepare('SELECT COUNT(order_id) AS total_orders FROM orders');
$totalOrdersQuery->execute();
$totalOrdersResult = $totalOrdersQuery->fetch(PDO::FETCH_ASSOC);
$totalOrders = $totalOrdersResult['total_orders'];

// Fetch the total number of shipments
$totalShipmentsQuery = $db->prepare('SELECT COUNT(shipment_id) AS total_shipments FROM shipment');
$totalShipmentsQuery->execute();
$totalShipmentsResult = $totalShipmentsQuery->fetch(PDO::FETCH_ASSOC);
$totalShipments = $totalShipmentsResult['total_shipments'];

// Fetch payment and shoes data per month
$paymentQuery = $db->prepare('
    SELECT 
        DATE_FORMAT(p.payment_date, "%Y-%m") AS month,
        SUM(p.amount) AS total_amount,
        SUM(oi.quantity) AS total_shoes
    FROM 
        payment p
    LEFT JOIN 
        orders o ON p.order_id = o.order_id
    LEFT JOIN 
        order_item oi ON o.order_id = oi.order_id
    WHERE 
        p.status = "completed"
    GROUP BY 
        DATE_FORMAT(p.payment_date, "%Y-%m")
    ORDER BY 
        month
');
$paymentQuery->execute();
$paymentResults = $paymentQuery->fetchAll(PDO::FETCH_ASSOC);

$months = [];
$amounts = [];
$quantities = [];

foreach ($paymentResults as $payment) {
    $months[] = $payment['month'];
    $amounts[] = (float) $payment['total_amount'];
    $quantities[] = (int) $payment['total_shoes'];
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
    <link rel="stylesheet" href="../asset/css/admin.css">
    <link rel="stylesheet" href="../asset/css/product.css">

    <!--Font family-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!--Icons-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.0/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Highcharts-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
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
                <a class="activ" href="admin.php">
                    <i class="bi bi-clipboard-pulse"></i>
                    <span>Dashboard</span>
                </a>
                <a href="admincategorie.php">
                    <i class="bi bi-bookmark-star"></i>
                    <span>Categories</span>
                </a>
                <a href="shoes.php">
                    <i class="fa-solid fa-socks"></i>
                    <span>Shoes</span>
                </a>
                <a href="newsletter.php">
                    <i class="bi bi-card-text"></i>
                    <span>Newsletter</span>
                </a>
                <a href="slide.php">
                    <i class="bi bi-file-image"></i>
                    <span>Slides</span>
                </a>
                <a href="orders.php">
                    <i class="bi bi-border"></i>
                    <span>Orders</span>
                </a>
                <a href="payment_history.php">
                    <i class="bi bi-credit-card-2-front"></i>
                    <span>Payment history</span>
                </a>
            </nav>
        </div>
        <div class="second-bloc">
            <div class="all-items" style='display:grid'>
                <div class="bloc-content">
                    <div>
                        <i class="bi bi-people"></i>
                        <p>Users</p>
                        <span><?php echo $totalUsers; ?></span>
                    </div>
                    <?php
                    if (isset($results) && !empty($results)) {
                        foreach ($results as $row) {
                            echo '<div>';
                            echo '<i class="bi bi-bookmark-star"></i>';
                            echo '<p>' . $row['category_name'] . '</p>';
                            echo '<span>Total Shoes: ' . $row['total_shoes'] . '</span>';
                            echo '<span>Total Stock: ' . $row['total_stock'] . '</span>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div>';
                        echo '<i class="bi bi-bookmark-star"></i>';
                        echo '<p>Categories</p>';
                        echo '<span>No data found</span>';
                        echo '</div>';
                    }
                    ?>
                    <div>
                        <i class="fa-solid fa-socks"></i>
                        <p>Shoes</p>
                        <span><?php echo $totalStock; ?></span>
                    </div>
                    <div>
                        <i class="bi bi-border"></i>
                        <p>Orders</p>
                        <span><?php echo $totalOrders; ?></span>
                    </div>
                    <div>
                        <i class="bi bi-flower2"></i>
                        <p>Shipment</p>
                        <span><?php echo $totalShipments; ?></span>
                    </div>
                </div>
                <div id="payment-chart" style="width: 100%; height: 400px; margin-top: 50px;"></div>
            </div>
        </div>
    </section>

    <script>

    document.addEventListener('DOMContentLoaded', function () {
        Highcharts.chart('payment-chart', {
            chart: {
                type: 'line',
                style: {
                    fontFamily: 'Poppins, sans-serif'
                }
            },
            title: {
                text: 'Monthly Payments and Shoes Purchased',
                style: {
                    fontFamily: 'Poppins, sans-serif'
                }
            },
            xAxis: {
                categories: <?php echo json_encode($months); ?>,
                crosshair: true,
                labels: {
                    style: {
                        fontFamily: 'Poppins, sans-serif'
                    }
                }
            },
            yAxis: [{
                title: {
                    text: 'Total Amount',
                    style: {
                        fontFamily: 'Poppins, sans-serif'
                    }
                },
                labels: {
                    style: {
                        fontFamily: 'Poppins, sans-serif'
                    }
                }
            }, {
                title: {
                    text: 'Total Shoes Purchased',
                    style: {
                        fontFamily: 'Poppins, sans-serif'
                    }
                },
                labels: {
                    style: {
                        fontFamily: 'Poppins, sans-serif'
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true,
                style: {
                    fontFamily: 'Poppins, sans-serif'
                }
            },
            series: [{
                name: 'Total Amount',
                data: <?php echo json_encode($amounts);?>
            }, {
                name: 'Total Shoes Purchased',
                data: <?php echo json_encode($quantities); ?>,
                yAxis: 1
            }]
        });
    });
</script>

</body>

</html>
