<?php
$num_products_on_each_page = 3;
$current_page = isset ($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
$stmt = $pdo->prepare('SELECT * FROM product order by date_add DESC LIMIT ?, ?');
$stmt->bindValue(1, ($current_page-1) * $num_products_on_each_page, PDO::PARAM_INT);
$stmt->bindValue(2, $num_products_on_each_page, PDO::PARAM_INT);
$stmt -> execute();
$products = $stmt ->fetchAll(PDO::FETCH_ASSOC);
$total_products = $pdo ->query('SELECT * FROM product') -> rowCount();
include 'header.php';
?>
<div class="main_home">
    <h1>Товары</h1>
    <p><?=$total_products?> Товара</p>
    <div class="new_products">
        <?php foreach ($products as $product): ?>
            <a href="index.php?page=product&id=<?=$product['id']?>" class="href_products">
                <img src="img/<?=$product['image']?>" alt="<?=$product['title']?>" width="250px" height="250px">
                <div class="title"><?=$product['title']?></div>
                <div class="price">
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
            <?php if ($current_page > 1): ?>
            <a href="index.php?page=products&p=<?=$current_page-1?>" id="goback_products"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
            <?php endif; ?>
            <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + count($products)): ?>
            <a href="index.php?page=products&p=<?=$current_page+1?>" id="gonext_products"><i class="fa fa-chevron-right" aria-hidden="true" width="55px" height="70px"></i></a>
            <?php endif; ?>
    </div>
    <div>
    </div>
</div>
<?=template_footer();?>