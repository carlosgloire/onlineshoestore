<?php
    session_start();
    require_once('../controllers/login_user.php');
    
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!--css-->
    <link rel="stylesheet" href="../asset/css/style.css">
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

<body>
    <!-- Le css de cette page se trouve dans product -->
    <section class="login-section">
        <div class="login">
            <h2>Login</h2>
            <form action="" method="post">
                <div class="all-inputs">
                    <i class="bi bi-envelope"></i>
                    <input type="email"  style="width:100%" name="mail" placeholder="Enter email" value="<?=isset($mail)?$mail:""?>">
                </div>
                <div class="all-inputs passwo">
                    <div class="pass">
                        <i class="bi bi-key"></i>
                        <input style="width:100%" class="password" name="password" type="password" placeholder="Enter password" value="<?=isset($password)?$password:""?>">
                    </div>
                    <div class="eyes">
                        <i class="bi bi-eye  close hidden"></i>
                        <i class="bi bi-eye-slash open"></i>
                    </div>
                </div>
                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot password?</a>
                </div>
                <div class="submit">
                    <input type="submit" name="login" value="Login">
                </div>
                <div class="account">
                    <p>Don't have an account?</p>
                    <a href="register.php">Sign up</a>
                </div>
                <p style="color:red;font-size:13px;text-align:center"><?=$error?></p>
            </form>
        </div>
    </section>

    <script src="../asset/javascript/app.js"></script>
</body>

</html>