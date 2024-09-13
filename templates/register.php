<?php require_once('../controllers/registerController.php')?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

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
<style>
    input{
        width: 100%;
    }
</style>
<body>
    <!-- Le css de cette page se trouve dans product -->
    <section class="login-section">
        <div class="login">
            <h2>Register</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="all-inputs">
                    <i class="bi bi-envelope"></i>
                    <input type="text" name="email" placeholder="Enter email" value="<?=isset($_POST['email'])?$_POST['email']:''?>">
                </div>
                <div class="all-inputs">
                    <i class="fa-regular fa-user"></i>
                        <input  name="fname" type="text" placeholder="Enter first name" value="<?=isset($_POST['fname'])?$_POST['fname']:''?>">
                </div>
                <div class="all-inputs">
                    <i class="fa-regular fa-user"></i>
                        <input  name="lname" type="text" placeholder="Enter second name" value="<?=isset($_POST['lname'])?$_POST['lname']:''?>">
                </div>
                <div class="all-inputs">
                    <i class="bi bi-telephone-x"></i>
                    <input type="text" id="phone" name="phone" placeholder="Enter phone number" value="<?=isset($_POST['phone'])?$_POST['phone']:''?>">
                </div>
                <div class="all-inputs" > 
                    <i class="fa-solid fa-earth-americas"></i>
                    <select id="country" name="country" style="width: 100%; border:none; outline:none;font-family: 'Poppins', sans-serif;">
                        <option value="select">Select a country...</option>
                        <option value="Rwanda">
                            Rwanda
                        </option>
                        <option value="Uganda">
                            Uganda
                        </option>
                        <option value="Burundi">
                            Burundi
                        </option>
                        <option value="Tanzania">
                            Tanzania
                        </option>
                        <option value="Kenya">
                            Kenya
                        </option>
                        <option value="Democratic-republic-of-congo">
                            Democratic Republic of Congo
                        </option>
                    </select>
                </div>
                <div class="all-inputs">
                    <i class="bi bi-bank"></i>
                    <input type="text" id="city" name="city" placeholder="Enter The full address" value="<?=isset($_POST['city'])?$_POST['city']:''?>">
                </div>
                <div class="all-inputs">
                    <i class="bi bi-file-earmark-image"></i>
                    <input type="file" id="uploadfile" name="uploadfile" accept=".jpg, .jpeg, .png" value="<?=isset($_POST['uploadfile'])?$_POST['uploadfile']:''?>" >
                </div>
                <div class="all-inputs">
                    <i class="bi bi-key"></i>
                    <input class="password" name="password" type="password" placeholder="Enter password" value="<?=isset($_POST['password'])?$_POST['password']:''?>">
                </div>  
                <div class="submit">
                    <input type="submit" name='register' value="Register">
                </div>
                <div class="account">
                    <p>Already have an account?</p>
                    <a href="login.php">Sign in</a>
                </div>
                <p style="color:red;font-size:13px;text-align:center"><?=$error?></p><p style="color: green;font-size:13px;text-align:center"><?=$success?></p>
            </form>
        </div>
    </section>

    <script src="../asset/javascript/app.js"></script>

</html>
