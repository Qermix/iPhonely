<?php 
    $num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <title>AppleGadget</title>
</head>
<body>
    <header class="header">
        <nav>
            <h1 class="logo_header"><a href="index.php?page=home"><img src="/img/logo.jpg" alt="Logo" width="55px">AppleGadget</a></h1>
            <div class="logo_link">
                <a href="#">Главная</a>
                <a href="#">Каталог</a>
                <a href="#">Контакты</a>
            </div>
            <i class="fa fa-bath" aria-hidden="true"></i>
            <i class="fa-facebook-official" aria-hidden="true"></i>
            <div class="logo_icons">
                    <?php if (isset ($_SESSION["login"])): ?>
                        
                    <?php endif;
                    if (!isset ($_SESSION['login'])): ?>
                    
                   <?php endif;?>
            </div>
        </nav>
    </header>
<main>