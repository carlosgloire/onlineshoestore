<?php
session_start();
require_once('../controllers/database/db.php');
require_once('../controllers/functions.php');
notconnected();
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!--css-->
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">
    <!--Font family-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
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
                                <p ><img style="width: 27px;height: 27px;object-fit:cover;border-radius:50%;cursor:pointer;" src="../templates/profile_photo/<?=$user['photo']?>" alt=""><i style="color: white;font-weight:bold" class="bi bi-plus"></i></p>
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

    <section class="home-section">
        <?php
            $query = $db->prepare('SELECT * FROM slides ');
            $query->execute();
            $images = $query->fetchAll();
        ?>
        <div class="home-text">
            <h1>Online shoes storing management system.</h1>
            <p>Welcome to our Online Shoe Store! Discover a wide range of stylish and comfortable shoes for every occasion. Sign up now to create your account and enjoy a personalized shopping experience, exclusive offers, and fast checkout. Start shopping today and step into a world of fashion and convenience!</p>
            <a href="login.php">Sign in</a>
        </div>

        <div class="home-images">
            <div class="gradient-overlay"></div>
            <?php foreach ($images as $image): ?>
                <p><img class="home-bg" src="slides_images/<?=$image['photo']?>" alt=""></p>
            <?php endforeach; ?>
        </div>

        <div class="circle-btn">
            <?php foreach ($images as $index => $image): ?>
                <div class="circle <?= $index === 0 ? 'active' : ''; ?>"></div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- About us -->
    <section class="about">
        <div class="title">
            <div>
                <h4>About</h4>
                <p></p>
            </div>
            <h2>about us</h2>
        </div>
        <div class="about-content">
            <div class="about-image">
                <p><img src="../asset/images/about.png" alt=""></p>
            </div>
            <div class="about-text">
                <p>Welcome to our Online Shoe Store, offering stylish, comfortable, and high-quality footwear from top brands and emerging designers for every occasion.</p>
                <p><i class="bi bi-check2-all"></i>Our mission is to make shoe shopping an enjoyable and hassle-free experience. With a user-friendly interface, secure payment options, and dedicated customer service, we strive to exceed your expectations at every step. We believe in offering exceptional value, which is why we continuously update our inventory with the latest trends and timeless classics at competitive prices.</p>
                <p><i class="bi bi-check2-all"></i>At our core, we value our customers and aim to build lasting relationships based on trust and satisfaction. Thank you for choosing us as your go-to destination for all your footwear needs. Step into style with us today!</p>
            </div>
        </div>
    </section>

    <!-- Why to choose us -->
    <section class="choose-us">
        <div class="title">
            <div>
                <h4>Choice</h4>
                <p></p>
            </div>
            <h2>Why to choose us</h2>
        </div>

        <div class="choose-us-content">
            <div class="choose-us-item">
                <i class="bi bi-bar-chart"></i>
                <div>
                    <h4>Wide Selection</h4>
                    <p> Discover a diverse range of styles and brands. Find the perfect pair for every occasion and taste.</p>
                </div>
            </div>
            <div class="choose-us-item">
                <i class="fa-solid fa-medal"></i>
                <div>
                    <h4>Quality Assurance</h4>
                    <p>We offer only the highest quality footwear. Enjoy long-lasting comfort and durability.</p>
                </div>
            </div>
            <div class="choose-us-item">
                <i class="fa-solid fa-award"></i>
                <div>
                    <h4>Competitive Prices</h4>
                    <p>Enjoy great value with our competitive pricing. Take advantage of regular discounts and special offers.</p>
                </div>
            </div>
            <div class="choose-us-item">
                <i class="fa-solid fa-bag-shopping"></i>
                <div>
                    <h4>Easy Shopping</h4>
                    <p>Experience a user-friendly website with seamless navigation. Fast and secure checkout makes shopping a breeze.</p>
                </div>
            </div>
            <div class="choose-us-item">
                <i class="bi bi-collection"></i>
                <div>
                    <h4>Customer Service</h4>
                    <p>Benefit from our dedicated customer support team. We're ready to assist you with any queries or issues.</p>
                </div>
            </div>
            <div class="choose-us-item">
                <i class="bi bi-truck"></i>
                <div>
                    <h4>Fast Shipping</h4>
                    <p>Receive your orders quickly with our reliable delivery services. Enjoy prompt and efficient shipping right to your door.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products -->
    <section class="product">
        <div class="title">
            <div>
                <h4>shoes</h4>
                <p></p>
            </div>
            <h2>Our shoes</h2>
        </div>
        <div class="prod-container">
        <?php
            $query=$db->prepare('SELECT * FROM shoes LIMIT 6');
            $query->execute();
            $shoes = $query->fetchAll();
            if(!$shoes){
                ?><div style="color:red"><?='No shoes already added '?></div><?php
            }else{
                foreach($shoes as $shoe){
                    $shoe_id= $shoe['shoe_id']
                    ?>
                        <div class="prod-item">
                            <a href="shoesdetails.php?shoe_id=<?=$shoe_id?>"><img src="shoes/<?=$shoe['photo']?>" alt=""></a>
                            <div class="item">
                                <div class="item-details">
                                    <p ><?=$shoe['name']?> </p>
                                    <span ><strong><?=$shoe['price']?> RWF</strong></span>
                                </div>
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
                                                echo $i <= $avg_rating ? "<i  class='bx bxs-star'></i>" : '<i style="font-size:12px;position:relative;top:-2px" class="fa-regular fa-star"></i>';
                                            }
                                            ?>
                                            <span style="color: black;color: gray; font-size:12px;position:relative;top:-2px">(<?php echo $avg_rating; ?>)</span>
                                        </div>
                                        
                                    <?php
                                ?>
                                <p style="font-size: 0.8rem;">Click 👉 <a style="font-weight: bold;color:#aca356da" href="reviews/review.html?shoe_id=<?=$shoe_id?>" >here</a> to review this shoe</p>
                                <div class="panier">
                                    <a href="shoesdetails.php?shoe_id=<?=$shoe_id?>"><i class="bi bi-cart"></i></a>
                                </div>
                            </div>
                        </div>

                    <?php
                }
            }

        ?>
        </div>
        <div class="more">
            <a href="product.php">View in details</a>
        </div>
    </section>

    <!-- Newsletter -->

    <section class="newsletter">
        <div class="newstext">
            <h3>Subscribe to our newsletter</h3>
            <p>Enter your email to stay updated on our new shoe collections and latest news. </p>
        </div>
        <div class="news-input">
            <form action="../controllers/news-letter.php" method="post">
                <input type="text" name="mail-newsLetter"  placeholder="Your email" id="">
                <button name="send-NewsLetter" >Send</button>
            </form>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories">
        <div class="title">
            <div>
                <h4>categories</h4>
                <p></p>
            </div>
            <h2>All categories</h2>
        </div>

        <div class="categories-content">
            <div class="categories-list">
                <ul>
                    <li class="active" data-filter="men-shoes">Men</li>
                    <li data-filter="wemen">Women</li>
                    <li data-filter="children">Children</li>
                </ul>
            </div>
            <div class="all-categories">
                <div class="shoes-item men-shoes">
                    <?php
                        $query = $db->prepare("SELECT photo,name,shoe_id FROM shoes WHERE type = 'Men'");
                        $query->execute();
                        $men = $query->fetchAll(PDO::FETCH_ASSOC);
                        if(! $men){
                            ?><p style="color: red;"><?= "No shoes added in this type"?></p><?php
                        }
                        else{
                            foreach($men as $man){
                                ?>
                                <a href="shoesdetails.php?shoe_id=<?=$man['shoe_id']?>">
                                    <div class="shoes">
                                        <p><img src="../templates/shoes/<?=$man['photo']?>" alt=""></p>
                                        <div class="overlay">
                                            <span><?=$man['name']?></span><br>
                                        </div>
                                    </div>
                                </a>
                            <?php
                            }
                        }
                    ?>
                 
                </div>

                <div class="shoes-item wemen">
                <?php
                        $query = $db->prepare("SELECT photo,name,shoe_id FROM shoes WHERE type = 'Women'");
                        $query->execute();
                        $men = $query->fetchAll(PDO::FETCH_ASSOC);
                        if(! $men){
                            ?><p style="color: red;"><?= "No shoes added in this type"?></p><?php
                        }
                        else{
                            foreach($men as $man){
                                ?>
                                <a href="shoesdetails.php?shoe_id=<?=$man['shoe_id']?>">
                                    <div class="shoes">
                                        <p><img src="../templates/shoes/<?=$man['photo']?>" alt=""></p>
                                        <div class="overlay">
                                            <span><?=$man['name']?></span><br>
                                        </div>
                                    </div>
                                </a>
                            <?php
                            }
                        }
                    ?>
                </div>

                <div class="shoes-item children">
                <?php
                        $query = $db->prepare("SELECT photo,name,shoe_id FROM shoes WHERE type = 'Children'");
                        $query->execute();
                        $men = $query->fetchAll(PDO::FETCH_ASSOC);
                        if(! $men){
                            ?><p style="color: red;"><?= "No shoes added in this type"?></p><?php
                        }
                        else{
                            foreach($men as $man){
                                ?>
                                <a href="shoesdetails.php?shoe_id=<?=$man['shoe_id']?>">
                                    <div class="shoes">
                                        <p><img src="../templates/shoes/<?=$man['photo']?>" alt=""></p>
                                        <div class="overlay">
                                            <span><?=$man['name']?></span><br>
                                        </div>
                                    </div>
                                </a>
                            <?php
                            }
                        }
                    ?>

                </div>
            </div>
        </div>

        <div class="more">
            <a href="shoes_by_categorie.php">More categories</a>
        </div>

    </section>

    <!-- Contacts -->
    <section class="contact">
        <div class="title">
            <div>
                <h4>contact</h4>
                <p></p>
            </div>
            <h2>Contact us</h2>
        </div>
        <div class="contact-container">
            <div class="contact-info">
                <div class="info">
                    <i class="bi bi-geo"></i>
                    <div>
                        <span>Location</span>
                        <p>Kigali, Rwanda</p>
                    </div>
                </div>
                <div class="info">
                    <i class="bi bi-envelope"></i>
                    <div>
                        <span>Email</span>
                        <a href="#">ndayisabarenzaho@gmail.com</a>
                    </div>
                </div>
                <div class="info">
                    <i class="bi bi-phone"></i>
                    <div>
                        <span>Call</span>
                        <a href="#">+250 791 460 743</a>
                    </div>
                </div>
            </div>
            <div class="contact-input">
                <form action="../controllers/contact_us.php" method="post">
                    <div>
                        <input type="text" name="noms" placeholder="Your name">
                        <input type="text" name="email" placeholder="Your email">
                    </div>
                    <input type="text" name="subject" placeholder="Message title">
                    <textarea placeholder="Write your message..." name="message" id="" rows="5"></textarea>
                    <div>
                        <button name="send" type="submit">Send message</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="writer">
            &copy; 2024 Online shoes storing management system. All rights reserved. <br> Developed by N.Gloire with ❤️
        </div>
    </footer>

    <script src="../asset/javascript/app.js"></script>
</body>

</html>

<!--kulim, belgrado-->