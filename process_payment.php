<?php
require 'vendor/autoload.php'; // Make sure to include the Composer autoload file

use GuzzleHttp\Client;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $public_key = "FLWPUBK_TEST-33e52f06e038469d6693230b8bc85b62-X";
    $secret_key = "FLWSECK_TEST-29acbf685af74fa9318b7a8c7e016da3-X";
    $tx_ref = "hooli-tx-" . time();
    $amount = $_POST['amount']; // Amount in the smallest currency unit
    $currency = $_POST['currency'];
    $redirect_url = "http://localhost/onlineShoeStore/flutterwavetest.php"; // Update as needed
    $payment_options = $_POST['paymentMethod'];

    $customer = [
        "email" => $_POST['email'],
        "phonenumber" => $_POST['phone'],
        "name" => $_POST['name'] // Get the customer's name from the form
    ];

    $customizations = [
        "title" => "My store",
        "description" => "Payment for items in cart",
        "logo" => "https://your-logo-url.com"
    ];

    $data = [
        "tx_ref" => $tx_ref,
        "amount" => $amount,
        "currency" => $currency,
        "redirect_url" => $redirect_url,
        "payment_options" => $payment_options,
        "customer" => $customer,
        "customizations" => $customizations
    ];

    $client = new Client();
    try {
        $response = $client->post('https://api.flutterwave.com/v3/charges?type=mobilemoneyrwanda', [
            'headers' => [
                'Authorization' => 'Bearer ' . $secret_key,
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        $body = $response->getBody();
        $result = json_decode($body, true);

        if ($result['status'] === 'success') {
            header('Location: ' . $result['data']['link']);
            exit();
        } else {
            echo 'Error: ' . $result['message'];
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request method.';
}
?>
