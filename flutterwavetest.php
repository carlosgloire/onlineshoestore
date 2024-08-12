<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flutterwave Payment</title>
</head>
<body>
    <h1>Flutterwave Payment Integration</h1>
    <form id="paymentForm">
        <div>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required />
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />
        </div>
        <div>
            <label for="amount">Amount</label>
            <input type="number" id="amount" name="amount" required />
        </div>
        <div>
            <label for="currency">Currency</label>
            <select id="currency" name="currency" required>
                <option value="RWF">RWF</option>
            </select>
        </div>
        <div>
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" />
        </div>
        <div>
            <label for="paymentMethod">Payment Method</label>
            <select id="paymentMethod" name="paymentMethod" required>
                <option value="mobilemoneyrwanda">Mobile Money (MTN Rwanda)</option>
                <option value="banktransfer">Bank Transfer</option>
            </select>
        </div>
        <button type="submit">Pay Now</button>
    </form>

    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var amount = document.getElementById('amount').value;
            var currency = document.getElementById('currency').value;
            var phone = document.getElementById('phone').value;
            var paymentMethod = document.getElementById('paymentMethod').value;

            FlutterwaveCheckout({
                public_key: "FLWPUBK_TEST-33e52f06e038469d6693230b8bc85b62-X",
                tx_ref: "hooli-tx-" + Date.now(),
                amount: amount,
                currency: currency,
                payment_options: paymentMethod,
                redirect_url: "http://localhost/onlineShoeStore/flutterwavetest.php",
                customer: {
                    email: email,
                    phonenumber: phone,
                    name: name,
                },
                customizations: {
                    title: "My store",
                    description: "Payment for items in cart",
                    logo: "https://static.vecteezy.com/system/resources/previews/003/092/544/non_2x/e-commerce-logo-with-pointer-and-shopping-bag-free-vector.jpg",
                },
                callback: function(data) {
                    console.log(data);
                },
                onclose: function() {
                    // callback function when the modal is closed
                },
            });
        });
    </script>
</body>
</html>
