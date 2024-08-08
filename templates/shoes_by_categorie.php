<?php
     session_start();
    require_once('../controllers/database/db.php');
    require_once('../controllers/functions.php');
    logout();
    // Fetch categories and their associated products
    $categories_query = $db->query("SELECT c.*,s.*
    FROM categories c
    LEFT JOIN shoes s ON c.category_id = s.category_id
    WHERE s.photo IS NOT NULL
    ORDER BY c.category_name, s.name");
    $categories = [];
    while ($row = $categories_query->fetch(PDO::FETCH_ASSOC)) {
    $categories[$row['category_id']]['category_name'] = $row['category_name'];
    if (!empty($row['photo'])) {
    $categories[$row['category_id']]['products'][] = [
    'photo' => $row['photo'],
    'name' => $row['name'],
    'price' => $row['price'],
    'shoe_id' => $row['shoe_id']
    ];
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
    <title>Categories</title>

    <!--css-->
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">

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
                                    <a href="dashboard.html">
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
                    <a class="home" href="index.php">Home</a>
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

    <!--Categories home page-->
    <section class="categories-home">
        <h3>#Categories</h3>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit debitis quis alias vero tempore?</p>
    </section>

    <section class="categories">
     
    <?php foreach ($categories as $cat_id => $category){
        if (!empty($category['products'])){
            ?>       
            <div class="categories-content">
                <h3><?=$category['category_name']?></h3>
                <div class="all-categories">
                    <i class="bi bi-chevron-left" id="left-arrow"></i>
                    <div class="categorie-images" id="categorie-images" >
                        <?php foreach ($category['products'] as $shoe): ?>
                            <a href="shoesdetails.php?shoe_id=<?=$shoe['shoe_id']?>">
                                <div class="prod-item">
                                    <img src="../templates/shoes/<?= $shoe['photo']?>" alt="<?=$shoe['name']?>">
                                    <div class="item">
                                        <div class="item-details">
                                            <p><?=$shoe['name']?></p>
                                            <span><?=$shoe['price']?> RWF</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <i class="bi bi-chevron-right" id="right-arrow"></i>
                </div>
            </div>

            <?php
        }
    }
    ?>
   
    </section>


    <script src="../asset/javascript/prod.js"></script>

</body>

</html>