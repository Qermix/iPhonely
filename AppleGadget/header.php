<?php 
    $num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>AppleGadget</title>
</head>
<body>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.1.min.js"></script>
    <header class="header">
        <nav>
           <a href="index.php?page=home" class="logo_links"><img src="img/logo.jpg" alt="Logo" width="55px" height="55px"> <h1 class="logo_header">AppleGadget</h1></a>
            <div class="logo_link">
                <a href="index.php?page=home">Главная</a>
                <a href="index.php?page=catalog">Каталог</a>
                <a href="index.php?page=contacts">Контакты</a>
            </div>

            <div class="logo_icons">
                    <?php if (isset ($_SESSION["login"])): ?>
                    <a href="index.php?page=logout"><i class="fa fa-sign-out fa-2x" aria-hidden="true"></i></a>
                    <a href="index.php?page=cart"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i></a> 
                    <?php endif;
                    if (!isset ($_SESSION['login'])): ?>
                    <a href="index.php?page=login"><i class="fa fa-sign-in fa-2x" aria-hidden="true"></i></a>
                   <?php endif;?>
            </div>
        </nav>
    </header>
<main>