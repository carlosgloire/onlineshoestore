<?php
require_once('../controllers/database/db.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = $db->prepare('SELECT * FROM shoes WHERE name LIKE :search');
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
        <div class="popup hidden-popup" >
            <div class="popup-container">
                <h3>Dear Admin,</h3>
                <p>Are you sure you want to delete  this shoe <br>from your system?</p>
                <div style="margin-top: 20px; justify-content:space-between;display:flex" class="popup-btn">
                    <button style="cursor:pointer;" class="cancel-popup icons-link">Cancel</button>
                    <button style="cursor:pointer;" class="delete-popup icons-link">Delete</button>
                </div>
            </div>
        </div>
        <script src="../asset/javascript/delete_popup.js"></script>
        <?php
    }
}
?>
