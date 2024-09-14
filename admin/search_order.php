<?php
require_once('../controllers/database/db.php');

// Get the search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch orders based on the search query
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
        oi.total_price,
        sh.shipment_country as shipping_country,
        sh.address as shipping_address,
        sh.whatsapp_number,
        sh.amount
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    JOIN order_item oi ON o.order_id = oi.order_id
    JOIN shoes s ON oi.shoe_id = s.shoe_id
    LEFT JOIN shipment sh ON o.order_id = sh.order_id
    WHERE 
        o.order_id LIKE :search 
        OR u.firstname LIKE :search 
        OR u.lastname LIKE :search 
        OR u.country LIKE :search
        OR o.order_date LIKE :search
        OR oi.quantity LIKE :search
        OR oi.total_price LIKE :search
    ORDER BY o.order_date DESC
");
$query->execute(['search' => '%' . $search . '%']);
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

echo json_encode($grouped_orders);
