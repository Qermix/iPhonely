<?php
if (isset($_GET['id'])){
    $stmt = $pdo ->prepare('SELECT * FROM product WHERE id = ?');
    $stmt -> execute([$_GET['id']]);
    $product = $stmt ->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        exit('Товар не найден!');
    }
}else {
    exit('Товар не найден!');
}
include 'header.php';?>
<div class="product">
    <img src="img/<?=$product['image']?>" alt="<?=$product['title']?>" width="500px" height="500px">
        <div class="h1"><?=$product['title']?>
        <div class="price_product">
           <?php if ($product['rrp'] > 0): ?>
                    <span><?=$product['rrp']?> &#8381;</span>
                    <span><s><?=$product['price']?> &#8381;</s></span>
                    <?php endif; ?>
                    <?php if($product['rrp'] == 0): ?>
                    <?=$product['price']?> &#8381;
                    <?php endif; ?>
        </div>
        
            <div>
        <form action="index.php?page=cart" method="post">
            <input type="number" name="quantity" value="1" min="1" max="<?=$product['quantity']?>" placeholder="Количество" required>
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="Добавить в корзину">
        </form>
        <div class="description">
            <?=$product['description']?>
        </div>
    </div>
    </div>
</div>
<?=template_footer()?>