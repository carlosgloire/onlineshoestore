<?php
session_start();
require_once('../controllers/database/db.php');
require_once('../controllers/functions.php');

logout();
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

// Fetch all products from the database
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = $db->prepare('SELECT * FROM shoes WHERE name LIKE :search OR brand LIKE :search OR color LIKE :search');
$query->execute(['search' => '%' . $search . '%']);
$shoes = $query->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <!--css-->
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">

    <!--Font family-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">

    <!--Icons-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.0/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>

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
                                <p ><img style="width: 27px;height: 27px;object-fit:cover;border-radius:50%;cursor:pointer;" src="../templates/profile_photo/<?=$user['photo']?>" alt=""><i style="color: white;font-weight:bold" class="bi bi-plus"></i></p>
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
                    <a class="home" href="#">Home</a>
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

    <!--Product home page-->
    <section class="pro-home">
        <h3>#Shoes</h3>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit debitis quis alias vero tempore?</p>
    </section>
    <div class="all-inputs" style="width:16%;margin-left:auto;margin-right:40px">
        <form method="GET" action="">
            <input type="search" id="search" name="search" placeholder="Search a shoe here" value="<?= htmlspecialchars($search) ?>">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
    <section class="product-type">
        <?php
        if (empty($shoes) && empty($search)) {
            echo '<p>No shoes already added</p>';
        } elseif (empty($shoes)) {
            echo '<p>No shoes found</p>';
        } else {
            foreach($shoes as $shoe){
                $shoe_id = $shoe['shoe_id'];
                $size = $shoe['size'];
                $color = $shoe['color'];
                $sizes = explode(',', $size);
                $colors = explode(',', $color);

                ?>
                     <div class="prod-type-container" style="margin-bottom: 40px;">
                        <div class="prod-type-item">
                            <p><img class="big-image" style="object-fit: cover;" src="../templates/shoes/<?=$shoe['photo']?>" alt=""></p>
                            <div style="display:flex; flex-wrap: wrap;">
                                <p ><img style="width: 98px;height:98px;cursor:pointer;object-fit:cover" class="small-image" src="../templates/shoes/<?=$shoe['photo']?>" alt="image product" ></p>
                                <?php
                                    $query = $db->prepare("SELECT shoe_image FROM  small_images WHERE shoe_id=$shoe_id");
                                    $query->execute();
                                    if($query->rowCount()>0){
                                        while($images = $query->fetch(PDO::FETCH_ASSOC)){
                                        ?>
                                            <p ><img style="width: 98px;height:98px;cursor:pointer;object-fit:cover" class="small-image" src="../templates/small_images/<?=$images['shoe_image']?>" alt="image product"></p>
                                        <?php
                                        }

                                    }

                                ?>

                            </div>
                        </div>
                        <div class="shoes-text">
                            <h4><?=$shoe['name']?></h4>
                            <span>Brand: <?=$shoe['brand']?></span><br>
                            <span>Colors: <?=$shoe['color']?></span><br>
                            <span>Sizes: <?=$shoe['size']?></span><br>
                            <span>Quantity in stock: <?=$shoe['stock']?> </span><br>
                            <span>Price: <?=$shoe['price']?> RWF</span>
                            <?php
                                $sql = 'SELECT AVG(rating) as avg_rating FROM reviews WHERE shoe_id = ?';
                                $stmt = $db->prepare($sql);
                                $stmt->execute([$shoe_id]);
                                $result = $stmt->fetch();
                                $avg_rating = round($result['avg_rating'], 1);
                            ?>
                            <div class="stars">
                                <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $avg_rating ? "<i class='bx bxs-star'></i>" : '<i style="font-size:12px;position:relative;top:-2px" class="fa-regular fa-star"></i>';
                                    }
                                ?>
                                <span style="color: black;color: gray; font-size:12px;position:relative;top:-2px">(<?=$avg_rating?>)</span>
                            </div>
                            <p><?=$shoe['description']?></p>
                            <div class="cart">
                                <div class="custom-dropdown">
                                    <button class="dropdown-btn" id="size-btn-<?=$shoe_id?>">Select size</button>
                                    <div class="dropdown-content" id="size-dropdown-<?=$shoe_id?>">
                                        <?php foreach($sizes as $s): ?>
                                            <label><input type="checkbox" value="<?=$s?>"> <?=$s?></label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="custom-dropdown">
                                    <button class="dropdown-btn" id="color-btn-<?=$shoe_id?>">Select Colors</button>
                                    <div class="dropdown-content" id="color-dropdown-<?=$shoe_id?>">
                                        <?php foreach($colors as $c): ?>
                                            <label><input type="checkbox" value="<?=$c?>"> <?=$c?></label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <form id="cart-form-<?=$shoe_id?>" action="../controllers/add_to_cart.php" method="post">
                                    <input type="hidden" name="shoe_id" value="<?=$shoe_id?>">
                                    <input type="hidden" name="selected_sizes" id="selected-sizes-input-<?=$shoe_id?>">
                                    <input type="hidden" name="selected_colors" id="selected-colors-input-<?=$shoe_id?>">
                                    <input type="submit" onclick="return storeSelections(<?=$shoe_id?>)" class="add-cart" name="ajouter_panier" value="Add to cart" style="color: #fff;padding: 10px 15px;display: flex;font-size: 0.8rem;width: fit-content;border-radius: 20px;background-color: #141b1fda;border:none; font-family: 'Poppins', sans-serif;">
                                </form>
                            </div>
                        </div>
                    </div>
                <?php
            }
        }
        ?>
    </section>
    <script src="../asset/javascript/prod.js"></script>
    <script>
        document.getElementById('search').addEventListener('input', function() {
            let searchQuery = this.value;
            let xhr = new XMLHttpRequest();
            xhr.open('GET', 'search_shoes.php?search=' + searchQuery, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.querySelector('.product-type').innerHTML = this.responseText;
                }
            };
            xhr.send();
        });

        function storeSelections(shoeId) {
            let selectedSizes = Array.from(document.querySelectorAll('#size-dropdown-' + shoeId + ' input[type="checkbox"]:checked')).map(cb => cb.value);
            let selectedColors = Array.from(document.querySelectorAll('#color-dropdown-' + shoeId + ' input[type="checkbox"]:checked')).map(cb => cb.value);

            document.getElementById('selected-sizes-input-' + shoeId).value = selectedSizes.join(',');
            document.getElementById('selected-colors-input-' + shoeId).value = selectedColors.join(',');

            return true; // Allows form submission
        }
    </script>
</body>

</html>
