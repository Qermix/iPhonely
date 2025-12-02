<?php
// search_products.php
include 'function.php';
$pdo = pdo_connect_mysql();

if (isset($_POST['search']) && !empty(trim($_POST['search']))) {
    $search = trim($_POST['search']);
    
    $sql = "SELECT * FROM gadgets WHERE name LIKE ? OR slogan LIKE ? ORDER BY name LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['%' . $search . '%', '%' . $search . '%']);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($products) > 0) {
        foreach($products as $product): ?>
        <a href="index.php?page=product&id=<?=$product['id']?>" class="text-decoration-none">
            <div class="ajax-result-item d-flex align-items-center p-2 border-bottom">
                <div class="flex-shrink-0">
                    <img src="img/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="50" height="50" class="rounded">
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="ajax-result-name fw-bold"><?= htmlspecialchars($product['name']) ?></div>
                    <div class="ajax-result-price text-primary"><?= number_format((float)str_replace([' ', '₽', 'руб.'], '', $product['price']), 0, '', ' ') ?> ₽</div>
                    <div class="ajax-result-color small text-muted"><?= htmlspecialchars($product['Color']) ?></div>
                </div>
            </div>
        </a>
        <?php endforeach;
    } else {
        echo '<div class="p-3 text-muted">Товары не найдены</div>';
    }
} else {
    echo '<div class="p-3 text-muted">Введите поисковый запрос</div>';
}
?>