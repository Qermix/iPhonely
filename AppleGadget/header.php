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
    <!-- Добавляем после <title> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
    <a href="index.php?page=logout" class="me-3" title="Выйти">
        <i class="fa fa-sign-out fa-2x" aria-hidden="true"></i>
    </a>
    <a href="index.php?page=cart" class="position-relative" title="Корзина">
        <i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i>
        <?php if(getCartCount() > 0): ?>
        <span class="cart-count badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" style="font-size: 10px; padding: 2px 5px;">
            <?= getCartCount() ?>
        </span>
        <?php endif; ?>
    </a> 
    <?php endif;
    if (!isset ($_SESSION['login'])): ?>
    <a href="index.php?page=login" title="Войти">
        <i class="fa fa-sign-in fa-2x" aria-hidden="true"></i>
    </a>
    <?php endif; ?>
</div>
        </nav>
    </header>
<main>