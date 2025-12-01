<?php
include 'header.php';

// Количество товаров на странице
$products_per_page = 9;

// Текущая страница
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
if ($current_page < 1) $current_page = 1;

// Обработка фильтров
$selected_categories = [];
$selected_colors = [];
$where_conditions = [];
$params = [];

// Получаем выбранные категории
if(isset($_GET['Категории']) && is_array($_GET['Категории'])) {
    $selected_categories = $_GET['Категории'];
    if(!empty($selected_categories)) {
        $placeholders = str_repeat('?,', count($selected_categories) - 1) . '?';
        $where_conditions[] = "Категории IN ($placeholders)";
        $params = array_merge($params, $selected_categories);
    }
}

// Получаем выбранные цвета
if(isset($_GET['color']) && is_array($_GET['color'])) {
    $selected_colors = $_GET['color'];
    if(!empty($selected_colors)) {
        $placeholders = str_repeat('?,', count($selected_colors) - 1) . '?';
        $where_conditions[] = "color IN ($placeholders)";
        $params = array_merge($params, $selected_colors);
    }
}

// Формируем базовый SQL запрос для подсчета общего количества
$count_sql = "SELECT COUNT(*) FROM gadgets";
if(!empty($where_conditions)) {
    $count_sql .= " WHERE " . implode(" AND ", $where_conditions);
}

// Получаем общее количество товаров
$stmt_count = $pdo->prepare($count_sql);
$stmt_count->execute($params);
$total_products = $stmt_count->fetchColumn();

// Вычисляем общее количество страниц
$total_pages = ceil($total_products / $products_per_page);

// Проверяем, чтобы текущая страница не превышала общее количество страниц
if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
}

// Формируем SQL запрос для выборки товаров с пагинацией
$sql = "SELECT * FROM gadgets";
if(!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}
$sql .= " ORDER BY id DESC LIMIT ?, ?";

// Вычисляем offset для пагинации
$offset = ($current_page - 1) * $products_per_page;

// Выполняем запрос с правильной привязкой параметров
$stmt = $pdo->prepare($sql);

// Привязываем параметры фильтров (если есть)
$param_index = 1;
foreach($params as $value) {
    $stmt->bindValue($param_index, $value, PDO::PARAM_STR);
    $param_index++;
}

// Привязываем параметры пагинации как INTEGER
$stmt->bindValue($param_index, $offset, PDO::PARAM_INT);
$stmt->bindValue($param_index + 1, $products_per_page, PDO::PARAM_INT);

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем все доступные категории и цвета для фильтров
$categories_stmt = $pdo->query("SELECT DISTINCT Категории FROM gadgets ORDER BY Категории");
$categories = $categories_stmt->fetchAll(PDO::FETCH_COLUMN);

$colors_stmt = $pdo->query("SELECT DISTINCT color FROM gadgets ORDER BY color");
$colors = $colors_stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<div class="catalog-container">
    <!-- Боковая панель фильтров -->
    <aside class="filters-sidebar">
        <form method="get" id="filterForm">
            <input type="hidden" name="page" value="catalog">
            <div class="filter-group">
                <h3>Категории</h3>
                <div class="filter-options">
                    <?php foreach($categories as $category): ?>
                        <div class="filter-option">
                            <input type="checkbox" name="Категории[]" id="Категории_<?= htmlspecialchars($category) ?>" 
                                   value="<?= htmlspecialchars($category) ?>" 
                                   <?= in_array($category, $selected_categories) ? 'checked' : '' ?>>
                            <label for="Категории_<?= htmlspecialchars($category) ?>">
                                <?= htmlspecialchars(ucfirst($category)) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-group">
                <h3>Цвет</h3>
                <div class="filter-options">
                    <?php foreach($colors as $color): ?>
                        <div class="filter-option">
                            <input type="checkbox" name="color[]" id="color_<?= htmlspecialchars($color) ?>" 
                                   value="<?= htmlspecialchars($color) ?>" 
                                   <?= in_array($color, $selected_colors) ? 'checked' : '' ?>>
                            <label for="color_<?= htmlspecialchars($color) ?>">
                                <?= htmlspecialchars(ucfirst($color)) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="apply-filters">Применить фильтры</button>
            </div>
        </form>
    </aside>

        <!-- Сетка товаров -->
        <div class="main_products">
            <div class="catalog_header">
                <h1 class="catalog-title">Каталог товаров</h1>
            </div>
        <div class="products-grid">
            <?php if(empty($products)): ?>
                <div class="no-products">
                    <h3>Товары не найдены</h3>
                    <p>Попробуйте изменить параметры фильтрации</p>
                </div>
            <?php else: ?>
                <?php foreach($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="img/<?= htmlspecialchars($product['img']) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <div class="product-info">
                            <p class="product-price"><?=$product['price'] ?> ₽</p>
                            <div class="product-actions">
                            <form action="index.php?page=cart" method="post">
                                 <input type="hidden" name="product_id" value="<?=$product['id']?>">
                                 <input type="submit" value="Добавить в корзину">
                            </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        </div>
        <div></div>

<!-- Пагинация -->
        <?php if($total_pages > 1): ?>
        <div class="pagination">
            <!-- Кнопка "Назад" -->
            <?php if($current_page > 1): ?>
                <?php
                $prev_params = $_GET;
                $prev_params['p'] = $current_page - 1;
                ?>
                <a href="index.php?<?= http_build_query($prev_params) ?>" class="pagination-arrow">
                    ‹
                </a>
            <?php else: ?>
                <span class="pagination-arrow disabled">‹</span>
            <?php endif; ?>

            <!-- Номера страниц -->
            <?php
            // Показываем страницы вокруг текущей
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            
            // Корректируем диапазон, если мы близко к началу или концу
            if($start_page > 1) {
                echo '<a href="index.php?' . http_build_query(array_merge($_GET, ['p' => 1])) . '">1</a>';
                if($start_page > 2) echo '<span class="pagination-dots">...</span>';
            }
            
            for($i = $start_page; $i <= $end_page; $i++):
                $page_params = $_GET;
                $page_params['p'] = $i;
            ?>
                <?php if($i == $current_page): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="index.php?<?= http_build_query($page_params) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if($end_page < $total_pages) {
                if($end_page < $total_pages - 1) echo '<span class="pagination-dots">...</span>';
                echo '<a href="index.php?' . http_build_query(array_merge($_GET, ['p' => $total_pages])) . '">' . $total_pages . '</a>';
            }
            ?>

            <!-- Кнопка "Вперед" -->
            <?php if($current_page < $total_pages): ?>
                <?php
                $next_params = $_GET;
                $next_params['p'] = $current_page + 1;
                ?>
                <a href="index.php?<?= http_build_query($next_params) ?>" class="pagination-arrow">
                    ›
                </a>
            <?php else: ?>
                <span class="pagination-arrow disabled">›</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

<script>
// Авто-применение фильтров при изменении чекбоксов
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
});
</script>
<?=template_footer()?>