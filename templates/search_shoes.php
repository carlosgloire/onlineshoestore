<?php
require_once('../controllers/database/db.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = $db->prepare('
    SELECT s.*
    FROM shoes s
    JOIN categories c ON s.category_id = c.category_id
    WHERE s.name LIKE :search
       OR s.brand LIKE :search
       OR s.color LIKE :search
       OR s.price LIKE :search
       OR c.category_name LIKE :search
');
$query->execute(['search' => '%' . $search . '%']);
$shoes = $query->fetchAll();

if (empty($shoes)) {
    echo '<p>No shoes found</p>';
} else {
    foreach ($shoes as $shoe) {
        $shoe_id = $shoe['shoe_id'];
        $size = $shoe['size'];
        $color = $shoe['color'];
        $sizes = explode(',', $size);
        $colors = explode(',', $color);

        echo '<div class="prod-type-container" style="margin-bottom: 40px;">
                <div class="prod-type-item">
                    <p><img class="big-image" style="object-fit: cover;" src="../templates/shoes/' . $shoe['photo'] . '" alt=""></p>
                    <div style="display:flex; flex-wrap: wrap;">
                        <p><img style="width: 98px;height:98px;cursor:pointer;object-fit:cover" class="small-image" src="../templates/shoes/' . $shoe['photo'] . '" alt="image product"></p>';
                        
        $image_query = $db->prepare("SELECT shoe_image FROM small_images WHERE shoe_id = :shoe_id");
        $image_query->execute(['shoe_id' => $shoe_id]);
        while ($images = $image_query->fetch(PDO::FETCH_ASSOC)) {
            echo '<p><img style="width: 98px;height:98px;cursor:pointer;object-fit:cover" class="small-image" src="../templates/small_images/' . $images['shoe_image'] . '" alt="image product"></p>';
        }
        
        echo '</div>
                </div>
                <div class="shoes-text">
                    <h4>' . $shoe['name'] . '</h4>
                    <span>Brand: ' . $shoe['brand'] . '</span><br>
                    <span>Colors: ' . $shoe['color'] . '</span><br>
                    <span>Sizes: ' . $shoe['size'] . '</span><br>
                    <span>Price: ' . $shoe['price'] . ' RWF</span>';
                    
        $rating_query = $db->prepare('SELECT AVG(rating) as avg_rating FROM reviews WHERE shoe_id = ?');
        $rating_query->execute([$shoe_id]);
        $result = $rating_query->fetch();
        $avg_rating = round($result['avg_rating'], 1);

        echo '<div class="stars">';
        for ($i = 1; $i <= 5; $i++) {
            echo $i <= $avg_rating ? "<i class='bx bxs-star'></i>" : '<i style="font-size:12px;position:relative;top:-2px" class="fa-regular fa-star"></i>';
        }
        echo '<span style="color: black;color: gray; font-size:12px;position:relative;top:-2px">(' . $avg_rating . ')</span>
                </div>
                <p>' . $shoe['description'] . '</p>
                <div class="cart">
                    <div class="custom-dropdown">
                        <button class="dropdown-btn" id="size-btn-' . $shoe_id . '">Select size</button>
                        <div class="dropdown-content" id="size-dropdown-' . $shoe_id . '">';
        foreach ($sizes as $s) {
            echo '<label><input type="checkbox" value="' . $s . '"> ' . $s . '</label>';
        }
        echo '</div>
                    </div>
                    <div class="custom-dropdown">
                        <button class="dropdown-btn" id="color-btn-' . $shoe_id . '">Select Colors</button>
                        <div class="dropdown-content" id="color-dropdown-' . $shoe_id . '">';
        foreach ($colors as $c) {
            echo '<label><input type="checkbox" value="' . $c . '"> ' . $c . '</label>';
        }
        echo '</div>
                    </div>
                    <form id="cart-form-' . $shoe_id . '" action="../controllers/add_to_cart.php" method="post">
                        <input type="hidden" name="shoe_id" value="' . $shoe_id . '">
                        <input type="hidden" name="selected_sizes" id="selected-sizes-input-' . $shoe_id . '">
                        <input type="hidden" name="selected_colors" id="selected-colors-input-' . $shoe_id . '">
                        <input type="submit" onclick="return storeSelections(' . $shoe_id . ')" class="add-cart" name="ajouter_panier" value="Add to cart" style="color: #fff;padding: 10px 15px;display: flex;font-size: 0.8rem;width: fit-content;border-radius: 20px;background-color: #141b1fda;border:none; font-family: \'Poppins\', sans-serif;">
                    </form>
                </div>
            </div>
        </div>';
    }
}
?>
