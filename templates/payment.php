<?php
session_start();
require_once('../controllers/database/db.php');
require_once('../controllers/functions.php');
notconnected();
if (!isset($_SESSION['order_id']) || !isset($_SESSION['user_id'])) {
    header('Location: cart.php');
    exit();
}

$order_id = $_SESSION['order_id'];
$user_id = $_SESSION['user_id'];

// Fetch order details
$order_query = $db->prepare('SELECT * FROM orders WHERE order_id = ?');
$order_query->execute([$order_id]);
$order = $order_query->fetch();

if (!$order) {
    header('Location: cart.php');
    exit();
}

$totalorder = $order['total_amount'];
$quantity_query = $db->prepare('SELECT SUM(quantity) AS total_quantity FROM order_item WHERE order_id = ?');
$quantity_query->execute([$order_id]);
$order_quantity = $quantity_query->fetchColumn();

// Fetch user details
$user_query = $db->prepare('SELECT email, phone, firstname,lastname FROM users WHERE user_id = ?');
$user_query->execute([$user_id]);
$user = $user_query->fetch();

if (!$user) {
    header('Location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <!--css-->
    <link rel="stylesheet" href="../asset/css/admin.css">
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">
    <!--Font family-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <!--Icons-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.0/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script type="text/javascript" src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
</head>
<body>
    <style>
        .continue-shopping {
            color: #fff;
            width: 100%;
            padding: 10px 15px;
            text-align: center;
            margin-top: 10px;
            border-radius: 8px;
            border: none;
            outline: none;
            background: #141b1fda;
            font-family: "Poppins", sans-serif;
        }

        #shipping-address-container {
            display: none;
        }
    </style>
    <section class="payment-section">
        <div class="payment-container">
            <h1 style="margin-bottom: 20px;">Complete Your Payment</h1>
            <form id="shipping-form">
                <div class="all-inputs">
                    <i class="bi bi-flag"></i>
                    <select id="country" name="country">
                        <option value="Rwanda">Rwanda</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Tanzania">Tanzania</option>
                        <option value="Burundi">Burundi</option>
                        <option value="DRC">DRC</option>
                    </select>                
                </div>
                <!-- Hidden by default for Rwanda -->
                <div id="shipping-address-container">
                    <div class="all-inputs">
                        <i class="bi bi-house"></i>
                        <input type="text" style="width:100%" id="address" name="address" placeholder="Write here the address where the package should be delivered">
                    </div>
                </div>
                <div class="all-inputs" >
                    <i class="bi bi-whatsapp"></i>
                    <input style="width:100%" type="text" id="whatsapp" name="whatsapp" placeholder="Provide the WhatsApp number">
                </div>

                <div class="all-inputs">
                    <i class="bi bi-credit-card"></i>
                    <select id="payment-method" name="payment_method">
                        <option value="mobilemoneyrwanda">Mobile Money (MTN Rwanda)</option>
                        <option value="banktransfer">Bank Transfer</option>
                    </select>
                </div>
                <p style="margin-bottom: 10px;margin-top:10px">Order Total: <span id="order-total"><?=number_format($totalorder, 2)?></span> RWF</p>
                <button type="button" onclick="makePayment()" class="continue-shopping">Pay Now</button>
            </form>
        </div>
    </section>
    <script>
        const countrySelect = document.getElementById('country');
        const orderTotalElement = document.getElementById('order-total');
        const paymentMethodSelect = document.getElementById('payment-method');
        const shippingAddressContainer = document.getElementById('shipping-address-container');
        let orderTotal = parseFloat(<?= json_encode($totalorder) ?>);
        const orderQuantity = parseInt(<?= json_encode($order_quantity) ?>);
        const orderId = <?= json_encode($order_id) ?>;

        countrySelect.addEventListener('change', function() {
            if (this.value !== 'Rwanda') {
                shippingAddressContainer.style.display = 'block'; // Show shipping address
                if (orderQuantity >= 20) {
                    orderTotal = parseFloat(<?= json_encode($totalorder) ?>) * 1.10;
                } else {
                    alert("For shipping out of Rwanda, the quantity of your shoes must be 20 or more.");
                    this.value = 'Rwanda';
                    orderTotal = parseFloat(<?= json_encode($totalorder) ?>);
                    shippingAddressContainer.style.display = 'none'; // Hide shipping address if Rwanda is selected
                }
            } else {
                orderTotal = parseFloat(<?= json_encode($totalorder) ?>);
                shippingAddressContainer.style.display = 'none'; // Hide shipping address for Rwanda
            }
            orderTotalElement.textContent = orderTotal.toFixed(2);
        });

        function makePayment() {
    const paymentMethod = paymentMethodSelect.value;
    const country = countrySelect.value;
    const address = document.getElementById('address').value;
    const whatsapp = document.getElementById('whatsapp').value;
    const amount = orderTotal.toFixed(2);
    const orderId = <?= $order_id ?>;

        // Determine the redirect URL based on the selected country
        const redirectUrl = country === 'Rwanda' ?
            `confirmcheckout.php?order_id=${orderId}&country=${country}&address=${encodeURIComponent(address)}&whatsapp=${encodeURIComponent(whatsapp)}&amount=${amount}` :
            `shipment.php?order_id=${orderId}&country=${country}&address=${encodeURIComponent(address)}&whatsapp=${encodeURIComponent(whatsapp)}&amount=${amount}`;

        FlutterwaveCheckout({
            public_key: "FLWPUBK_TEST-33e52f06e038469d6693230b8bc85b62-X",
            tx_ref: "RX1_" + Math.floor((Math.random() * 1000000000) + 1),
            amount: orderTotal,
            currency: "RWF",  // Currency set to RWF
            country: "RW",
            payment_options: paymentMethod,
            redirect_url: redirectUrl,
            meta: {
                consumer_id: 23,
                consumer_mac: "92a3-912ba-1192a",
            },
            customer: {
                email: "<?php echo $user['email']; ?>",
                phone_number: "<?php echo $user['phone']; ?>",
                name: "<?php echo $user['firstname']; ?>",
            },
            callback: function (data) {
                console.log(data);
                window.location.href = redirectUrl;
            },
            onclose: function() {
                // close modal
            },
            customizations: {
                title: "Best Payment Gateway",
                description: "Payment for your order",
                logo: "img/favicon-32x32.png",
            },
        });
    }

    </script>
</body>
</html>
