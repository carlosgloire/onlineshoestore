<?php 
    require_once('../controllers/database/db.php');
    require_once('../controllers/add_shoes.php');
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a shoe</title>

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

<body >

    <!-- Admin header -->
    <header class="header-admin" >
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
                <a  href="slide.php">
                    <i class="bi bi-file-image"></i>
                    <span>Slides</span>
                </a>
                <a href="orders.html">
                    <i class="bi bi-border"></i>
                    <span>Orders</span>
                </a>
                <a href="shipment.html">
                    <i class="bi bi-flower2"></i>
                    <span>Shipment</span>
                </a>
            </nav>
        </div>
        <div class="second-bloc" style="padding-top: 140px;">
            <div class="newsletter-form">
                <form action=""  method="post" enctype="multipart/form-data">
                    <p style="color:red;font-size:13px;text-align:center"><?=$error?></p>
                    <p style="color:green;font-size:13px;text-align:center"><?=$success?></p>
                    <h3>Add a shoe</h3>
                    <div class="all-inputs">
                        <input type="text" id="name" name="name" placeholder="Shoe name" value="<?= isset($name)? $name:''?>">
                    </div>
                    <div class="all-inputs">
                        <input type="text" id="brand" name="brand" placeholder="Brand"  value="<?= isset($brand)? $brand:''?>">
                    </div>
                    <div class="all-inputs">
                        <select name="type" id="">
                            <option value="type">Select types</option>
                            <option value="Men">Men</option>
                            <option value="Wemen">Wemen</option>
                            <option value="Children">Children</option>
                        </select>
                    </div>
                    <div class="all-inputs">
                        <select name="category" id="">
                            <option value="category">Select shoes categories</option>
                            <?php 
                                $query = $db->prepare('SELECT * FROM categories');
                                $query->execute();
                                while($categorie = $query->fetch()){
                                    ?>
                                        <option value="<?=$categorie['category_id']?>"><?=$categorie['category_name']?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                    <div>
                        <p style="text-align: left;margin-left:22px;margin-top:10px">Select size</p>
                        <div class="all-inputs">
                            <div class="sizes" style="display: flex; gap:20px;flex-wrap:wrap">
                                <?php 
                                $shoe_sizes = ['26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46'];
                                foreach($shoe_sizes as $shoe_size){
                                    echo '<div><label>'.$shoe_size.' <input value="'.$shoe_size.'" type="checkbox" name="size[]"> </label></div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="all-inputs">
                        <input type="text" id="color" name="color" placeholder="Colors"  value="<?= isset($color)? $color:''?>">
                    </div>
                    <div class="all-inputs">
                        <input type="number" id="price" name="price" placeholder="Price" style="width:100%"  value="<?= isset($price)? $price:''?>">
                    </div>
                    <div class="all-inputs">
                        <input type="number" id="stock" name="stock" placeholder="Quantity in stock" style="width:100%"  value="<?= isset($stock)? $stock:''?>">
                    </div>
                    <div class="all-inputs">
                        <textarea id="description" name="description" rows="4" placeholder="Shoe description"><?= isset($description)? $description:''?></textarea>
                    </div>
                    <div class="all-inputs">
                        <input type="file" id="file" name="uploadfile" >
                    </div>

                    <div class="submit">
                        <input type="submit" name="add" value="Add shoes">
                    </div>
                </form>
            </div>
        </div>
    </section>

</body>

</html>