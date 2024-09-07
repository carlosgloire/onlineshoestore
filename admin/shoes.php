<?php
session_start();
require_once('../controllers/functions.php');
require_once('../controllers/database/db.php');
notAdmin();
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoes</title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>

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
                <a href="admincategorie.php">
                    <i class="bi bi-bookmark-star"></i>
                    <span>Categories</span>
                </a>
                <a class="activ" href="shoes.php">
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
                <a href="payment_history.php">
                    <i class="bi bi-credit-card-2-front"></i>
                    <span>Payment history</span>
                </a>
            </nav>
        </div>

        <div class="second-bloc">
            <div style="display: flex;justify-content:space-around;align-items:center">
                <div class="all-inputs" style="align-items:center;">
                    <form action="" method="GET" >
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input style="width: initial;" type="search" id="search" name="search" placeholder="Search a shoe here">
                    </form>
                </div>
                <div class="add-shoes">
                    <a href="addshoes.php">Add a shoes</a>
                </div>
            </div>
            <div id="product-container" style="margin-bottom: 30px;" class="prod-container">  
                <?php
                $searchQuery = '';
                if (isset($_GET['search'])) {
                    $searchQuery = htmlspecialchars($_GET['search']);
                    $query = $db->prepare('SELECT * FROM shoes WHERE name LIKE :search ' );
                    $query->execute(['search' => '%' . $searchQuery . '%']);
                } else {
                    $query = $db->prepare('SELECT * FROM shoes ORDER BY shoe_id DESC');
                    $query->execute();
                }
                $shoes = $query->fetchAll();
                if(!$shoes){
                    ?><div style="color:red"><?='No shoes found'?></div><?php
                }else{
                    foreach($shoes as $shoe){
                        ?>
                            <div class="prod-item">
                                <a href="shoesdetails.php?shoe_id=<?=$shoe['shoe_id']?>"><p><img src="../templates/shoes/<?=$shoe['photo']?>" alt="<?=$shoe['name']?>"></p></a>
                                <div class="item">
                                    <div class="item-details">
                                        <p><?=$shoe['name']?></p>
                                        <span><?=$shoe['price']?> RWF</span>
                                        <span><?=$shoe['stock']?> in stock</span>
                                    </div>
                                </div>
                                <div class="categorie-item" style="box-shadow:none">
                                    <div>
                                        <a href="small_images.php?shoe_id=<?=$shoe['shoe_id']?>"><i class="bi bi-file-image"></i></a>
                                        <a href="editshoe.php?shoe_id=<?=$shoe['shoe_id']?>"><i class="bi bi-pen"></i></a>
                                        <button class="delete" gallery_id="<?= $shoe['shoe_id'] ?>"><i class="bi bi-trash3"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
      
    </section>
    <?= popup_shoe()?>
    <script>
        document.getElementById('search').addEventListener('input', function() {
            let searchQuery = this.value;
            let xhr = new XMLHttpRequest();
            xhr.open('GET', 'search_shoes.php?search=' + searchQuery, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById('product-container').innerHTML = this.responseText;
                }
            };
            xhr.send();
        });
        /*
        Event Listener: Listens for input changes in the search field.
        AJAX Request: Sends a request to search_shoes.php with the search query as a parameter.
        Response Handling: Updates the product list container with the response from the server, which includes the search results.
         */
    </script>
    <script src="../asset/javascript/delete_popup.js"></script>
</body>
</html>
