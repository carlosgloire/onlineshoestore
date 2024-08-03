<?php
session_start();
require_once('../controllers/database/db.php');

$name = $brand = $size = $color = $price = $description = $photo = '';

if(isset($_GET['shoe_id']) && !empty($_GET['shoe_id'])){
    $shoe_id = $_GET['shoe_id'];
    $query = $db->prepare('SELECT * FROM shoes WHERE shoe_id = ?');
    $query->execute([$shoe_id]);
    $shoe = $query->fetch();
    if($shoe){
        $name = $shoe['name'];
        $brand = $shoe['brand'];
        $size = $shoe['size'];
        $color = $shoe['color'];
        $price = $shoe['price'];
        $stock= $shoe['stock'];
        $description = $shoe['description'];
        $photo = $shoe['photo'];
    } else {
        echo '<script>alert("Shoe ID not found.");</script>';
        echo '<script>window.location.href="index.php";</script>';
    }
} else {
    echo '<script>alert("No shoe ID provided.");</script>';
    echo '<script>window.location.href="index.php";</script>';
}

$query = $db->prepare('SELECT * FROM small_images WHERE shoe_id = ?');
$query->execute([$shoe_id]);


$sizes = explode(',', $size);
$colors = explode(',', $color);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$name?></title>
    <!--css-->
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">
    <!--Font family-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <!--Icons-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.0/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>

<body>

    <section class="product-type">
        <div class="prod-type-container" style="margin-top: 15%;">
            <div class="prod-type-item">
                <p><img class="big-image" src="../templates/shoes/<?=$photo?>" alt=""></p>
                <div style="display:flex; flex-wrap: wrap;">
                    <p><img style="width: 97px;height:97px;cursor:pointer;object-fit:cover" class="small-image" src="../templates/shoes/<?=$photo?>" alt=""></p>
                    <?php
                        while( $small_img = $query->fetch()){
                            ?>
                                <p><img style="width: 97px;height:97px;cursor:pointer;object-fit:cover" class="small-image" src="../templates/small_images/<?=$small_img['shoe_image']?>" alt=""></p>

                            <?php
                        }
                    ?>
                </div>
            </div>
            <div class="shoes-text">
                <h4><?=$name?></h4>
                <span>Brand: <?=$brand?></span><br>
                <span>Colors: <?=$color?></span><br>
                <span>Sizes: <?=$size?></span><br>
                <span>Quantity in stock: <?=$stock?> </span><br>
                <span>Price: <?=$price?> RWF</span><br>
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
                    <span style="color: black;color: gray; font-size:12px;position:relative;top:-2px">(<?=$avg_rating?>)</span>
                </div>
                <p style="font-size: 0.8rem;">Click 👉 <a style="font-weight: bold;color:#aca356da" href="reviews/review.html?shoe_id=<?=$shoe_id?>" >here</a> to review this shoe</p>
                <p><?=$description?></p>
                <div class="cart">
                    <div class="custom-dropdown">
                        <button class="dropdown-btn" id="size-btn">Select size</button>
                        <div class="dropdown-content" id="size-dropdown">
                            <?php foreach($sizes as $s): ?>
                                <label><input type="checkbox" value="<?=$s?>"> <?=$s?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="custom-dropdown">
                        <button class="dropdown-btn" id="color-btn">Select Colors</button>
                        <div class="dropdown-content" id="color-dropdown">
                            <?php foreach($colors as $c): ?>
                                <label><input type="checkbox" value="<?=$c?>"> <?=$c?></label>
                            <?php endforeach; ?>
                        </div>
                    </div> 
                    <form id="cart-form" action="../controllers/add_to_cart.php" method="post">
                        <input type="hidden" name="shoe_id" value="<?=$shoe_id?>">
                        <input type="hidden" name="selected_sizes" id="selected-sizes-input">
                        <input type="hidden" name="selected_colors" id="selected-colors-input">
                        <input type="submit" onclick="return storeSelections()" class="add-cart" name="ajouter_panier" value="Add to cart" style="color: #fff;padding: 10px 15px;display: flex;font-size: 0.8rem;width: fit-content;border-radius: 20px;background-color: #141b1fda;border:none; font-family: 'Poppins', sans-serif;">
                    </form>
                </div>
            </div>
        </div>
        
    </section>

    <script>
        let selectedSizes = [];
        let selectedColors = [];

        document.querySelectorAll('#size-dropdown input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    selectedSizes.push(this.value);
                } else {
                    selectedSizes = selectedSizes.filter(size => size !== this.value);
                }
                console.log('Selected sizes:', selectedSizes);
            });
        });

        document.querySelectorAll('#color-dropdown input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    selectedColors.push(this.value);
                } else {
                    selectedColors = selectedColors.filter(color => color !== this.value);
                }
                console.log('Selected colors:', selectedColors);
            });
        });

        function storeSelections() {
            if (selectedSizes.length === 0) {
                alert("Please select at least one size.");
                return false;
            }
            if (selectedColors.length === 0) {
                alert("Please select at least one color.");
                return false;
            }
            document.getElementById('selected-sizes-input').value = JSON.stringify(selectedSizes);
            document.getElementById('selected-colors-input').value = JSON.stringify(selectedColors);
            return true;
        }
    </script>
    
    <script src="../asset/javascript/prod.js"></script>
</body>
</html>
