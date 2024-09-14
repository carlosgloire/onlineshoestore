<?php

require_once('../controllers/database/db.php');
require_once('../controllers/delete_account.php');
require_once('../controllers/functions.php');
notconnected();
logout();
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $_SESSION['user_id'] = $user_id; // Ensure session user_id is set
} elseif (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    echo '<script>alert("No user ID provided.");</script>';
    echo '<script>window.location.href="templates/";</script>';
    exit;
}

$query = $db->prepare('SELECT * FROM users WHERE user_id = ?');
$query->execute([$user_id]);
$user = $query->fetch();

if ($user) {
    $photo = $user['photo'];
    $fname = $user['firstname'];
    $lname = $user['lastname'];
    $email = $user['email'];
    $phone = $user['phone'];
    $country_fetched = $user['country'];
    $city = $user['city'];
} else {
    echo '<script>alert("User ID not found.");</script>';
    echo '<script>window.location.href="templates/";</script>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!--css-->
    <link rel="stylesheet" href="../asset/css/admin.css">
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/product.css">
    <!--Font family-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <!--Icons-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.0/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
    <section class="user-dashboard">
        <div class="dashboard">
            <div class="left-side">
                <nav>
                    <a href="dashboard.php">
                        <i class="bi bi-clipboard-pulse"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="act" href="#">
                        <i class="bi bi-person-check"></i>
                        <span>My profile</span>
                    </a>
                    <a href="payment_history.php">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>Payment history</span>
                    </a>
                    <a href="#">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <form action="" method="post" style="margin-top: -3px;">
                            <button name="logout" style="color: white;"><span>Log out</span></button>
                        </form>
                    </a>
                </nav>
            </div>
        </div>
        <div class="right-side">
            <div class="profil-details">
                <div class="categories-list">
                    <ul>
                        <li class="active" data-filter="men-shoes">My profile</li>
                        <li data-filter="wemen">Change profile</li>
                    </ul>
                </div>
                <div class="myprofil shoes-item men-shoes">
                    <div class="myprofil-details">
                        <div class="profil-intro">
                            <h3>My profile</h3>
                            <p><img src="profile_photo/<?=$photo?>" alt=""></p>
                        </div>
                        <div class="all-info">
                            <div>
                                <i class="bi bi-person-circle"></i>
                                <p>First name:</p>
                                <span><?=$fname?></span>
                            </div>
                            <div>
                                <i class="bi bi-person-circle"></i>
                                <p>Last name:</p>
                                <span><?=$lname?></span>
                            </div>
                            <div>
                                <i class="bi bi-envelope"></i>
                                <p>Email:</p>
                                <span><?=$email?></span>
                            </div>
                            <div>
                                <i class="bi bi-telephone"></i>
                                <p>Phone number:</p>
                                <span><?=$phone?></span>
                            </div>
                            <div>
                                <i class="bi bi-flag"></i>
                                <p>Country:</p>
                                <span><?=$country_fetched?></span>
                            </div>
                            <div>
                                <i class="bi bi-app-indicator"></i>
                                <p>Address:</p>
                                <span><?=$city?></span>
                            </div>
                            <p style="color: red;cursor:pointer;text-align:center" class="delete" id="open">Delete Account <i  class="bi bi-trash3" ></i></p>

                        </div>
                        <?=popup_delete_count($error)?>
                        <script src="../asset/javascript/popup_update_account.js"></script>
                    </div>
                </div>
                <div class="shoes-item wemen">
                    <div class="myprofil-details profil-item">
                        <h3 style="margin-top: 20px; text-align:center;">Update my profile</h3>
                        <form action="../controllers/update_userprofile.php" method="POST" enctype="multipart/form-data">
                            <div>
                                <input type="text" name="fname" value="<?= htmlspecialchars($fname) ?>" required>
                                <input type="text" name="lname" value="<?= htmlspecialchars($lname) ?>" required>
                                <input type="text" name="email" value="<?= htmlspecialchars($email) ?>" required>
                                <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                                <select id="country" name="country" style="width: 100%; border:none; outline:none;font-family: 'Poppins', sans-serif;padding:10px">
                                    <option value="select">Select a country...</option>
                                    <option value="Rwanda" <?= $country_fetched == 'Rwanda' ? 'selected' : '' ?>>Rwanda</option>
                                    <option value="Uganda" <?= $country_fetched == 'Uganda' ? 'selected' : '' ?>>Uganda</option>
                                    <option value="Burundi" <?= $country_fetched == 'Burundi' ? 'selected' : '' ?>>Burundi</option>
                                    <option value="Tanzania" <?= $country_fetched == 'Tanzania' ? 'selected' : '' ?>>Tanzania</option>
                                    <option value="Kenya" <?= $country_fetched == 'Kenya' ? 'selected' : '' ?>>Kenya</option>
                                    <option value="Democratic-republic-of-congo" <?= $country_fetched == 'Democratic-republic-of-congo' ? 'selected' : '' ?>>Democratic Republic of Congo</option>
                                </select>
                                <input type="text" name="city" value="<?= htmlspecialchars($city) ?>" required>
                                <input type="file" name="uploadfile">
                                <input type="password" name="current_password" placeholder="Enter your current password" required>
                            </div>
                            <div class="sub">
                                <input class="delete" type="submit" name="edit" value="Update profile">
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../asset/javascript/app.js"></script>
</body>
</html>
