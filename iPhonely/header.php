<?php 
    $num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>iPhonely</title>
</head>
<body>
    <header class="header">
        <div>
            <h1 class="logo_header"><a href="index.php?page=home"><b>i</b>Phonely</a></h1>
            <nav class="nav_header">
                <ul>
                    <li><a href="index.php?page=products">Товары</a></li>
                     <?php if (isset ($_SESSION["login"])): ?>
                    <li><a href="index.php?page=logout">Выйти</a></li>
                    <li><a href="index.php?page=cart">
                        <i class="fas fa-shopping-cart"></i>
                        <span><?=$num_items_in_cart?></span>
                    </a></li>
                    <?php endif;
                    if (!isset ($_SESSION['login'])): ?>
                   <li><a href="index.php?page=login">Войти</a></li>
                   <?php endif;?>
                </ul>
            </nav>
        </div>
    </header>
<main>