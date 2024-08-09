
<?php
function popup_shoe(){
    ?>
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
<?php
}
?>
<?php
function popup_category(){
    ?>
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
<?php
}
?>
<?php
function popup_slides(){
    ?>
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
<?php
}
?>

<?php
function popup_order_item(){
    ?>
        <div class="popup hidden-popup" >
        <div class="popup-container">
            <h3>Dear User,</h3>
            <p>Are you sure you want to delete  this item<br>from your order?</p>
            <div style="margin-top: 20px; justify-content:space-between;display:flex" class="popup-btn">
                <button style="cursor:pointer;" class="cancel-popup icons-link">Cancel</button>
                <button style="cursor:pointer;" class="delete-popup icons-link">Delete</button>
            </div>
        </div>
    </div>
<?php
}
?>
<?php

function popup_delete_count($error) {
    ?>
        <div class="popup <?= isset($error) ? '' : 'hidden-popup' ?>">
            <div class="popup-container">
                <form action="" method="post">
                    <h3>Dear User,</h3>
                    <p>To delete your account please enter your password</p>
                    <div class="pass">
                        <input style="width:100%" class="password" name="password2" type="password" placeholder="Enter password" value="<?= isset($_POST['password2']) ? htmlspecialchars($_POST['password2']) : '' ?>">
                    </div>
                    <div style="margin-top: 20px; justify-content:space-between;display:flex" class="popup-btn">
                        <button type="button" style="cursor:pointer;" class="cancel-popup icons-link" >Cancel</button>
                        <button name="delete" style="cursor:pointer;" class="delete-popup icons-link">Delete</button>
                    </div>
                    <?php
                    if (isset($error) && !empty($error)) {
                        ?><p style="color:red;text-align:center;margin-top:10px"><?=$error?></p><?php
                    }
                    ?>
                </form>
            </div>
        </div>
    
    <?php
}


?>
<?php
function notconnected(){
    if (! isset($_SESSION['user'])) {
        // Redirect to the login page if not logged in
        header("Location: login.php");
        exit();
    }
}
function logout(){
    if(isset($_POST['logout'])){
        session_destroy();
        header('location:../templates/');
        exit();
    }
}

