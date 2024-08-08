<?php
session_start();
require_once('../controllers/database/db.php');

if (!isset($_SESSION['order_id'])) {
    // Redirect to cart if no order ID is set
    header('Location: cart.php');
    exit();
}

// Retrieve order details
$order_id = $_SESSION['order_id'];
$order_query = $db->prepare('SELECT * FROM orders WHERE order_id = ?');
$order_query->execute([$order_id]);
$order = $order_query->fetch();

if (!$order) {
    // Redirect to cart if order not found
    header('Location: cart.php');
    exit();
}
$totalorder=$order['total_amount']/1351.5;
// Retrieve the total quantity of the order
$quantity_query = $db->prepare('SELECT SUM(quantity) AS total_quantity FROM order_item WHERE order_id = ?');
$quantity_query->execute([$order_id]);
$order_quantity = $quantity_query->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">
    <!--Font family-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <!--Icons-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.0/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="https://www.paypal.com/sdk/js?client-id=ASRoly_qC6lVWj--7YxvUQTKlMISK6IG3c-Js9mvMJuxVd4UW9U3BX87aSjgnE1_V-arJ6KmLaOVEujq"></script>
</head>
<body>
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
                <div class="all-inputs">
                    <i class="bi bi-house"></i>
                    <input type="text" style="width:100%" id="address" name="address" placeholder="write here the address where the package should delivered">
                </div>
                <div class="all-inputs" style="margin-bottom: 20px;">
                    <i class="bi bi-whatsapp"></i>
                    <input style="width:100%" type="text" id="whatsapp" name="whatsapp" placeholder="Provide the whatsapp number ">
                </div>

                <p style="margin-bottom: 10px;">Order Total: <span id="order-total"><?=$order['total_amount']?></span>RWF</p>
                <div id="paypal-button-container"></div>
            </form>
        </div>
    </section>

    <script>
        const countrySelect = document.getElementById('country');
        const orderTotalElement = document.getElementById('order-total');
        let orderTotal = parseFloat(<?=$totalorder?>);
        const orderQuantity = parseInt(<?=$order_quantity?>);

        countrySelect.addEventListener('change', function() {
            if (this.value !== 'Rwanda') {
                if (orderQuantity >= 20) {
                    orderTotal = parseFloat(<?=$totalorder?>) * 1.10;
                } else {
                    alert("For shipping out of Rwanda, the quantity of your shoes must be 20 or more than 20.");
                    this.value = 'Rwanda';
                    orderTotal = parseFloat(<?=$totalorder?>);
                }
            } else {
                shippingDetails.style.display = 'none';
                orderTotal = parseFloat(<?=$totalorder?>);
            }
            orderTotalElement.textContent = orderTotal.toFixed(2);
        });

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: orderTotal.toFixed(2)
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);

                    // Gather shipping details
                    const country = document.getElementById('country').value;
                    const address = document.getElementById('address').value;
                    const whatsapp = document.getElementById('whatsapp').value;

                    // Send shipping details to the server
                    fetch('save_shipping_details.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            order_id: <?=$order_id?>,
                            country: country,
                            address: address,
                            whatsapp: whatsapp
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect to a success page
                            window.location.href = 'payment_success.php';
                        } else {
                            alert('There was an issue saving the shipping details.');
                        }
                    });

                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
