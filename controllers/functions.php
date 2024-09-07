
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
            <p>Are you sure you want to delete  this slide <br>from your system?</p>
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
function popup_small_images(){
    ?>
        <div class="popup hidden-popup" >
        <div class="popup-container">
            <h3>Dear Admin,</h3>
            <p>Are you sure you want to delete this image <br>from your system?</p>
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
        header("Location: ../templates/login.php");
        exit();
    }
}

function notAdmin(){
    
$db = new PDO("mysql:host=localhost;dbname=online_shoes_store", "root", "",
 [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $query = $db->prepare('SELECT role FROM users WHERE role =?');
    $query->execute(array($_SESSION['role']));
     
    if(($_SESSION['role']) != 'admin'){
        header("Location: ../templates/");
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

// Function to send email
function sendStockAlertEmail($mysqli, $admin_email) {
    // Check if shoes have reached stock of 5 or less
    $query = "SELECT * FROM shoes WHERE stock <= 5";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $mailer = require("../controllers/mail/mailer.php");

        // Initialize variables for email content
        $subject = "Alert: Shoe Stock Update";
        $email_body = <<<END
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f5f5f5;
                    padding: 20px;
                }
                .container {
                    background-color: #fff;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    white-space: pre-wrap; /* Preserve white space and line breaks */
                }
                .shoe {
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                    border-bottom: 1px solid #ddd;
                }
                a {
                    color: #1a73e8;
                    text-decoration: none;
                }
                a:hover {
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <p><strong>Alert: Shoe Stock Update</strong></p>
END;

        // Loop through shoes with stock 5 or less and append to email body
        while ($row = $result->fetch_assoc()) {
            $shoe_name = $row['name'];
            $shoe_stock = $row['stock'];
            $shoeId = $row['shoe_id'];
            $update_link = "http://localhost/onlineShoeStore/admin/editshoe.php?shoe_id=$shoeId";

            $email_body .= <<<END
                <div class="shoe">
                    <p><strong>Shoe:</strong> $shoe_name</p>
                    <p><strong>Current Stock:</strong> $shoe_stock</p>
                    <p><a href="$update_link">Update Stock for $shoe_name</a></p>
                </div>
END;
        }

        // Close the email body
        $email_body .= <<<END
                <p>Please take necessary action.</p>
            </div>
        </body>
        </html>
END;

        $mailer->setFrom('noreply@yourdomain.com', 'ONLINE SHOES STORING MANAGEMENT SYSTEM'); // Set a default sender
        $mailer->Subject = html_entity_decode($subject); // Decode HTML entities in subject
        $mailer->CharSet = 'UTF-8'; // Set charset to UTF-8
        $mailer->Body = $email_body;
        $mailer->isHTML(true);

        // Set the recipient to the admin email
        $mailer->addAddress($admin_email, 'Admin'); // Admin's email address

        try {
            $mailer->send();
            echo "";
        } catch (Exception $e) {
            echo "";
        }
    } else {
        echo "";
    }
}
