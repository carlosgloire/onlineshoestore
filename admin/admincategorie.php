<?php
session_start();
require_once('../controllers/functions.php');
require_once('../controllers/database/db.php');
require_once('../controllers/functions.php');
notAdmin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>

    <!--css-->
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/admin.css">
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

    <!-- Admin header -->
    <header class="header-admin">
        <div>
            <h2>Dashboard</h2>
        </div>
        <div class="admin-menu">
            <i class="bi bi-list"></i>
            <i class="bi bi-x"></i>
        </div>
    </header>

    <section class="admin-section">
        <div class="first-bloc">
            <nav>
                <a href="admin.php">
                    <i class="bi bi-clipboard-pulse"></i>
                    <span>Dashboard</span>
                </a>
                <a class="activ" href="#">
                    <i class="bi bi-bookmark-star"></i>
                    <span>Categories</span>
                </a>
                <a href="shoes.php">
                    <i class="fa-solid fa-socks"></i>
                    <span>Shoes</span>
                </a>
                <a href="newsletter.php">
                    <i class="bi bi-card-text"></i>
                    <span>Newsletter</span>
                </a>
                <a href="slide.php">
                    <i class="bi bi-file-image"></i>
                    <span>Slides</span>
                </a>
                <a href="orders.php">
                    <i class="bi bi-border"></i>
                    <span>Orders</span>
                </a>
                <a href="shipment.html">
                    <i class="bi bi-flower2"></i>
                    <span>Shipment</span>
                </a>
            </nav>
        </div>
       
        <div class="second-bloc">
            <div class="add-shoes" style="margin: auto;margin-bottom:20px">
                <a href="addcategory.php">Add a category</a>
            </div>    
            <div class="categorie-details">
                <?php
                    $query = $db->prepare('SELECT * FROM CATEGORIES');
                    $query->execute();
                    $categories = $query->fetchAll();
                    if(! $categories){
                        ?><p style="color: red;"><?='No categorie already added'?></p><?php
                    }
                    foreach($categories as $categorie){
                        ?>
                            <div class="categorie-item">
                                <h3><?=$categorie['category_name']?></h3>
                                <p><?=$categorie['description']?></p>
                                <div>
                                    <a href="editcategorie.php?category_id=<?=$categorie['category_id']?>"><i class="bi bi-pen"></i></a>
                                    <button class="delete" gallery_id="<?= $categorie['category_id'] ?>"><i class="bi bi-trash3"></i></button>
                                </div>
                            </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </section>
    <?=popup_category()?>
    <script src="../asset/javascript/popup_delete_category.js"></script>
</body>

</html>