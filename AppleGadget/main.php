<?php
$stmt = $pdo->prepare('SELECT * FROM product order by date_add DESC LIMIT 3');
$stmt -> execute();
$recently_added_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>
<div id="slogan">
    <h2>Любишь iPhone? Мы - тоже.</h2>
    <p>Новые и сертифицированные модели, быстрая доставка, trade-in и гарантия. Выбери любимый цвет — мы сделаем остальное.</p>
    <button href="index.php?page=products" onclick="location.href='index.php?page=products'">Подобрать iphone</button>
    <img src="img/Apple-Iphone-17.png" alt="iPhone 17 pro">
</div>
<div class="main_home">
    <h2>Новинки</h2>
    <div class="new_products">
        <?php foreach ($recently_added_products as $product): ?>
        <a href="index.php?page=product&id=<?=$product['id']?>">
            <img src="img/<?=$product['image']?>" alt="<?=$product['title']?>" width="250px" height="250px">
            <div class="title title_product_home"><?=$product['title'] ?></div>
            <div class="price_product_home">
                <?php if ($product['rrp'] > 0): ?>
                    <span><?=$product['rrp']?> &#8381;</span>
                    <span><s><?=$product['price']?> &#8381;</s></span>
                    <?php endif; ?>
                    <?php if($product['rrp'] == 0): ?>
                    <?=$product['price']?> &#8381;
                    <?php endif; ?>
                </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?=template_footer()?>