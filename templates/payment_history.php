<?php
session_start();
require_once('../controllers/functions.php');
require_once('../controllers/database/db.php');
logout();
notconnected();

$user_id = $_SESSION['user_id'];

$sql = "
SELECT 
    p.payment_date, 
    SUM(oiu.quantity) AS shoes_purchased, 
    p.payment_method, 
    p.amount, 
    p.status 
FROM 
    payment p 
JOIN 
    order_user ou ON p.order_id = ou.order_id 
JOIN 
    order_item_user oiu ON ou.order_id = oiu.order_id 
WHERE 
    ou.user_id = :user_id
GROUP BY 
    p.payment_date, 
    p.payment_method, 
    p.amount, 
    p.status
ORDER BY
    p.payment_date DESC     
";

$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment history</title>

    <!--css-->
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">
    <link rel="stylesheet" href="../asset/css/admin.css">
    <link rel="stylesheet" href="../asset/css/payment_history.css">
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
                    <a class="act" href="dashboard.php">
                        <i class="bi bi-clipboard-pulse"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="profil.php?user_id=<?=$_SESSION['user_id']?>">
                        <i class="bi bi-person-check"></i>
                        <span>My profile</span>
                    </a>
                    <a href="payment_history.php">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>Payment history</span>
                    </a>
                    <a href="#">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <form action="" method="post" style="margin-top: -3px;">
                            <button name="logout" style="color: white;"><span>Log out</span></button>
                        </form>
                    </a>
                </nav>
            </div>
        </div>

        <div class="right-side container" >
            <h2 style="text-align: center;margin-top:20px;margin-bottom:10px;">Payment history</h2>
            <table>
            <tr>
                <th>Payment Date</th>
                <th>Shoes Purchased</th>
                <th>Payment Method</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
            <?php foreach ($results as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                <td><?php echo htmlspecialchars($row['shoes_purchased']); ?></td>
                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                <td><?php echo htmlspecialchars($row['amount']); ?></td>
                <td class="<?php echo 'status-' . strtolower(htmlspecialchars($row['status'])); ?>">
                    <?php echo htmlspecialchars($row['status']); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div>
    </section>

    <script src="../asset/javascript/prod.js"></script>
    <script src="../asset/javascript/popup_delete_oderItem.js"></script>

</body>

</html>
