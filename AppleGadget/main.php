<?php
$titles = $pdo->prepare('SELECT * FROM gadgets where id = 1'); 
$titles -> execute();
$title = $titles->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo->prepare('SELECT * FROM gadgets order by quantity DESC LIMIT 4');
$stmt -> execute();
$most_popular_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>
<div id="slogan">
    <h2>iPhone 17 Pro</h2>
    <p>Стильный. Легкий. Прочный. Современный</p>
    <button href="index.php?page=products" onclick="location.href='index.php?page=products'" class="btn btn-secondary">Купить</button>
</div>
<div class="main_home">
    <h2 class="section_title">Хиты продаж</h2>
    <div class="popular_products">
        <?php foreach ($most_popular_products as $product): ?>
        <div class="porduct_card">
        <a href="index.php?page=product&id=<?=$product['id']?>">
            <img src="img/<?=$product['img']?>" alt="<?=$product['name']?>" width="250px" height="250px">
            <div class="title_product_home"><?=$product['name'] ?></div>
            <div class="price_product_home">
                    <?=$product['price']?> &#8381;
                </div>
                <form action="index.php?page=cart" method="post">
                    <input type="hidden" name="product_id" value="<?=$product['id']?>">
                    <input type="submit" value="Добавить в корзину">
                </form>
        </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="advantages">
        <h2 class="section_title">Наши преимущества</h2>
        <div class="feature-grid">
            <div class="feature-item">
                <div class="feature-icon">✓</div>
                <h3>Гарантия 1 год</h3>
                <p>Официальная гарантия на всю технику</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">✓</div>
                <h3>Оригинальная техника</h3>
                <p>Только сертифицированные товары Apple</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">✓</div>
                <h3>Быстрая доставка</h3>
                <p>Доставка по Москве за 2 часа</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">✓</div>
                <h3>Умная рассрочка</h3>
                <p>Рассрочка 0% на 12 месяцев</p>
            </div>
        </div>
</div>
<?=template_footer()?>