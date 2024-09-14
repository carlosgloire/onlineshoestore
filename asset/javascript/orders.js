document.getElementById('search').addEventListener('input', function() {
    var query = this.value;

    if (query.length > 0) {
        fetch('search_order.php?search=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                var orderContainer = document.getElementById('order-container');
                orderContainer.innerHTML = ''; // Clear existing content

                for (var firstname in data) {
                    for (var lastname in data[firstname]) {
                        var userDetails = data[firstname][lastname];
                        var userHtml = '<h4 style="margin-left: 30px;">' + firstname + ' ' + lastname + '</h4>' +
                            '<p style="margin-left: 30px; color: #555;">' + userDetails.city + ', ' + userDetails.country + ' - ' + userDetails.phone + '</p>';

                        for (var date in userDetails.dates) {
                            userHtml += '<div class="date-group">' +
                                '<div class="date" style="display: flex;color: #9a9a9a;font-weight: 500;font-size: 0.8rem;margin-top: 20px;justify-content: flex-end;margin-right:30px">' + date + '</div>';

                            for (var order_id in userDetails.dates[date]) {
                                var items = userDetails.dates[date][order_id];
                                var totalOrderPrice = 0;
                                var pendingOrderFound = false;
                                var shipmentCountry = items[0].shipping_country || '';
                                var shippingAddress = items[0].shipping_address || '';
                                var whatsappNumber = items[0].whatsapp_number || '';
                                var amt = items[0].amount || '';

                                items.forEach(function(item) {
                                    totalOrderPrice += item.total_price;
                                    if (item.order_status === 'pending') {
                                        pendingOrderFound = true;
                                    }
                                });

                                userHtml += '<div class="our-panier-prod">' +
                                    '<div class="order-prod">' +
                                    '<div><p><img src="../templates/shoes/' + items[0].photo + '" alt=""></p></div>' +
                                    '<div><h4>Sizes selected</h4><span>' + items[0].size + '</span></div>' +
                                    '<div><h4>Colors selected</h4><span>' + items[0].color + '</span></div>' +
                                    '<div><h4>Quantity </h4><input type="number" value="' + items[0].quantity + '" disabled></div>' +
                                    '<div><h4>Price </h4><span>' + items[0].price + ' RWF</span></div>' +
                                    '</div>' +
                                    '<div class="price">' +
                                    '<h4>Total price</h4>' +
                                    '<span>' + items[0].total_price + ' RWF</span>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div style="margin-left: 30px;" class="total-price">' +
                                    '<h4 style="font-weight:normal">Total Order Price: ' + totalOrderPrice + ' RWF</h4>' +
                                    
                                    '</div>';

                                if (shipmentCountry || shippingAddress || whatsappNumber || amt) {
                                    userHtml += '<div style="margin-left: 30px;" class="shipping-info">' +
                                        (shipmentCountry ? '<p>Shipment Country: ' + shipmentCountry + '</p>' : '') +
                                        (shippingAddress ? '<p>Shipping Address: ' + shippingAddress + '</p>' : '') +
                                        (whatsappNumber ? '<p>WhatsApp Number: ' + whatsappNumber + '</p>' : '') +
                                        (amt ? '<p>Amount with shipment: ' + amt + ' RWF</p>' : '') +
                                        '</div>';
                                }

                                userHtml += '<div style="margin-left: 30px;" class="status">' +
                                    '<p>Status: ' + (pendingOrderFound ? '<span style="color: red;">Pending</span>' : '<span style="color: green;">Completed</span>') + '</p>' +
                                    '</div>' +
                                    '<hr>';
                            }
                            userHtml += '</div>';
                        }

                        orderContainer.innerHTML += userHtml;
                    }
                }
            });
    } else {
        // If search query is empty, reload all orders
        location.reload();
    }
});
