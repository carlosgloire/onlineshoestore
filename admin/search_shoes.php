<?php
session_start();
require_once('../controllers/database/db.php');
require_once('../controllers/functions.php');
notAdmin();
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
    echo '<div style="color:red">No shoes found</div>';
} else {
    foreach ($shoes as $shoe) {
        ?>
        <div class="prod-item">
            <a href="shoesdetails.php?shoe_id=<?=$shoe['shoe_id']?>"><p><img src="../templates/shoes/<?=$shoe['photo']?>" alt="<?=$shoe['name']?>"></p></a>
            <div class="item">
                <div class="item-details">
                    <p><?=$shoe['name']?></p>
                    <span><?=$shoe['price']?> RWF</span>
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

        <?= popup_shoe()?>
        <script src="../asset/javascript/delete_popup.js"></script>
        <?php
    }
}
?>
