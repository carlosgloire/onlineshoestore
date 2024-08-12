<?php
session_start();
require_once('../controllers/database/db.php');
require_once('../controllers/functions.php');
logout();
// Handle form submission to update the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])){
    if (isset($_POST['products']) && !empty($_POST['products'])) {
        foreach ($_POST['products'] as $key => $product) {
            $shoe_id = $product['shoe_id'];
            $sizes = explode(',', str_replace(' ', '', $product['sizes']));
            $colors = explode(',', str_replace(' ', '', $product['colors']));
            $quantity = (int)$product['quantity'];

            // Update session data
            $_SESSION['panier'][$key]['shoe_id'] = $shoe_id;
            $_SESSION['panier'][$key]['sizes'] = $sizes;
            $_SESSION['panier'][$key]['colors'] = $colors;
            $_SESSION['panier'][$key]['quantity'] = $quantity;
        }
    }
}

// Calculate the total quantity of all orders
$total_quantity = 0;
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $total_quantity += (isset($item['quantity']) ? $item['quantity'] : 0);
    }
}

$user = null;
if (isset($_SESSION['user_id'])) {
    $query = $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $query->execute(['user_id' => $_SESSION['user_id']]);
    $user = $query->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <!--css-->
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">
    <!--Font family-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <!--Icons-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.0/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"
    />
</head>

<body>
    <!--Header part-->
    <header>
        <div class="header-content">
            <div class="logo">
                <p><img src="../asset/images/logo.png" alt=""></p>
            </div>
            <div class="list">
                <?php
                    if (isset($_SESSION['user']) && $_SESSION['user']){
                        ?>
                            <div class="indicator">
                                <p><img style="width: 30px;height: 30px;object-fit:cover;border-radius:50%;cursor:pointer" src="../templates/profile_photo/<?=$user['photo']?>" alt=""><i style="color: white;font-weight:bold" class="bi bi-plus"></i></p>
                                <div class="dashboard-user">
                                    <a href="dashboard.php">
                                        <i class="bi bi-person-bounding-box"></i>
                                        <span><?=$user['firstname']." ".$user['lastname']?></span>
                                    </a>
                                    <?php
                                        $admin=$user['role'];
                                        if($admin=='admin'){
                                            ?>
                                                 <a href="../admin/admin.php">
                                                    <i class="bi bi-clipboard-pulse"></i>
                                                    <span>Administration</span>
                                                </a>
                                            <?php
                                        }
                                    ?>
                                   
                                    <a href="profil.php?user_id=<?=$user['user_id']?>">
                                        <i class="bi bi-person-check"></i>
                                        <span>My profile</span>
                                    </a>
                                    <a href="#" style="display: flex;align-items:center;gap:5px">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                        <form action="" method="post" style="margin-top: -3px;">
                                            <button name="logout"><span>Log out</span></button>
                                        </form>
                                    </a>
                                </div>
                            </div>
                        <?php
                    }
                ?>

                <div class="list-details">
                    <a class="home" href="../templates/">Home</a>
                    <a href="#">About us</a>
                    <a href="product.php">Shoes</a>
                    <a href="shoes_by_categorie.php">Categories</a>
                    <a href="#">Contacts</a>
                </div>
                <div class="list-panier">
                    <a class="panier" href="cart.php"><i class="bi bi-cart"></i></a>
                    <span><?= ($total_quantity > 0) ? str_pad($total_quantity, 2, '0', STR_PAD_LEFT) : '00' ?></span>
                </div>
            </div>
        </div>
    </header>

    <section class="pro-home">
        <h3>#Cart</h3>
        <p>This part shows all the shoes you desire to order, colors , sizes and quantity can be modified just by placing the cursor and edit then click on update cart</p>
    </section>

    <section class="our-panier">
        <div class="our-panier-prod">
            <?php
            if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
                $subtotal = 0;
                ?>
                <form method="POST" action="">
                    <?php
                    foreach ($_SESSION['panier'] as $key => $item) {
                        if (!isset($item['shoe_id'])) {
                            continue;
                        }

                        $product_id = $item['shoe_id'];
                        $query = $db->prepare('SELECT * FROM shoes WHERE shoe_id = ?');
                        $query->execute([$product_id]);
                        $produit = $query->fetch();

                        if ($produit) {
                            $quantity = isset($item['quantity']) ? $item['quantity'] : 0;
                            $total_price = $produit['price'] * $quantity;
                            $subtotal += $total_price;
                            ?>
                            <div class="our-panier-prod" >
                                <div class="order-prod">
                                    <div>
                                        <p><img src="../templates/shoes/<?=$produit['photo']?>" alt=""></p>
                                    </div>
                                    <div>
                                        <h4>Shoe name</h4>
                                        <span><?=$produit['name']?></span>
                                    </div>
                                    <div>
                                        <h4>Sizes selected</h4>
                                        <input class="size" style="width:100%;color: #9a9a9a;font-family: 'Poppins', sans-serif;'" type="text" name="products[<?=$key?>][sizes]" value="<?=implode(', ', $item['sizes'])?>">
                                    </div>
                                    <div>
                                        <h4>Colors selected</h4>
                                        <input style="width:100%; color: #9a9a9a;font-family: 'Poppins', sans-serif;'" type="text" name="products[<?=$key?>][colors]" value="<?=implode(', ', $item['colors'])?>">
                                    </div>
                                    <div >
                                        <h4>Quantity</h4>
                                        <input type="hidden" name="products[<?=$key?>][shoe_id]" value="<?=$product_id?>">
                                        <input id="quantity-<?=$key?>" style="text-align:center; color: #9a9a9a;font-family: 'Poppins', sans-serif;" type="number" name="products[<?=$key?>][quantity]" value="<?=$quantity?>" min="1" data-stock="<?=$produit['stock']?>">
                                    </div>
                                    <div>
                                        <h4>Price</h4>
                                        <span><?=$produit['price']?></span>
                                    </div>
                                    <div class="delete">
                                        <a class="remove" onclick="removeFromCart(<?=$product_id?>)" href="#">
                                            <i class="bi bi-trash3"></i> delete
                                        </a>
                                    </div>
                                </div>
                                <div class="price">
                                    <h4>Total price</h4>
                                    <span><?=$total_price?> RWF</span>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <p style="text-align: center;"><strong>Total orders Price: <?=$subtotal?> RWF</strong></p>

                    <div class="our-buttons">
                        <button type="submit" name="update_cart" class="continue-shopping">Update cart</button>
                        <button type="button"><a style="color:white" href="../controllers/process_order.php">Order now</a> </button>
                    </div>
                </form>
                <?php
            } else {
                ?>
                <p style="text-align:center;color:#ff0000">No product added to cart, your cart is empty !!</p>
                <?php
            }
            ?>
        </div>
    </section>
    <script>

        function removeFromCart(productId) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = 'remove_from_cart.php';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'shoe_id';
            input.value = productId;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('change', function() {
                const stock = parseInt(this.getAttribute('data-stock'));
                const value = parseInt(this.value);
                if (value > stock) {
                    alert('The quantity entered is not available in stock.');
                    this.value = stock;
                }
            });
        });

    </script>
</body>
</html>
