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

logout();