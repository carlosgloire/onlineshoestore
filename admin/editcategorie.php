<?php
    session_start();
    require_once('../controllers/editcategory.php');
    require_once('../controllers/functions.php');
    notAdmin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter</title>

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
                <a class="activ" href="admincategorie.php">
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
                <a href="payment_history.php">
                    <i class="bi bi-credit-card-2-front"></i>
                    <span>Payment history</span>
                </a>
            </nav>
        </div>
        <div class="second-bloc">
            <div class="newsletter-form">
                <form action="" method="post">
                    <h3>Edit category</h3>
                    <div class="all-inputs">
                        <input type="text" name="categorie" value="<?=$category_name?>">
                    </div>
                    <div class="all-inputs">
                        <textarea id="description" name="description" rows="4" ><?=$category_desc?></textarea>
                    </div>

                    <div class="submit">
                        <input type="submit" name="edit" value="Save change">
                    </div>
                    <p style="color:red;font-size:13px;text-align:center"><?=$error?></p>
                    <p style="color:green;font-size:13px;text-align:center"><?=$success?></p>
                </form>
            </div>
        </div>
    </section>

</body>

</html>