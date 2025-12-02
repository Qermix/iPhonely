<?php
// ajax_search.php
include 'function.php';
$pdo = pdo_connect_mysql();
if (isset($_POST['search']) && !empty(trim($_POST['search']))) {
    $search = trim($_POST['search']);
    
    $sql = "SELECT * FROM gadgets WHERE name LIKE ? ORDER BY name LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['%' . $search . '%']);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($products) > 0) {
        foreach($products as $product): ?>
        <link rel="stylesheet" href="style.css">
            <a href="index.php?page=product&id=<?=$product['id']?>" class="ajax-result-item">
                <div class="ajax-result-info">
                    <div class="ajax-result-name"><?= htmlspecialchars($product['name']) ?></div>
                    <div class="ajax-result-price"><?=$product['price'] ?> ₽</div>
                    <div class="ajax-result-Color"><?= htmlspecialchars($product['Color']) ?></div>
                </div>
            </a>
        <?php endforeach;
    } else {
        echo '<div class="no-results">Товары не найдены</div>';
    }
} else {
}
?>