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

if (isset($_POST['search'])) {
$search = $pdo->real_escape_string($_POST['search']);
$sql = $search ?
"SELECT name FROM gadgets WHERE name LIKE '%$search%'" :
"SELECT name FROM gadgets";
$result = $pdo->query($sql);
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
echo "<tr><td>" . $row['name'] . "</td></tr>";
}
} else {
echo "<tr><td colspan='1'>Нет совпадений</td></tr>";
}
}
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
    <div class="searc">
        <div class="input-group mb-3">
            <input type="text" id="search" class="form-control" placeholder="Поиск товаров..." aria-label="Поиск товаров">
            <button class="btn btn-outline-secondary" type="button" id="searchButton">
                <i class="fa fa-search"></i>
            </button>
        </div>
        <div id="searchResults" class="position-absolute bg-white border rounded p-3" style="display: none; z-index: 1000; width: 400px;"></div>
    </div>
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
                        <a href="index.php?page=product&id=<?=$product['id']?>">
                        <div class="product-image">
                            <img src="img/<?= htmlspecialchars($product['img'])?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <div class="product-info">
                            <p class="title_product_home"><?=$product['name']?></p>
                            <p class="under_title_slogan"><?=$product['slogan']?></p>
                            <p class="product-price"><?=isset($product['price']) ? number_format((float)$product['price'], 0, '', ' ') : '0'  ?> ₽</p>
                            <div class="product-actions">
                            <button class="add-to-cart-btn" data-product-id="<?=$product['id']?>">
                                         Добавить в корзину
                             </button>
                            </div>
                            </div>
                            </a>
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
})
/*** */
$(document).ready(function() {
    console.log('Catalog page loaded'); // Для отладки
    // Добавление товара в корзину с каталога
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var productId = $(this).data('product-id');
        var button = $(this);
        var productCard = $(this).closest('.product-card');
        var productName = productCard.find('.title_product_home').text();
        
        console.log('Adding product to cart:', productId, productName);
        
        // Блокируем кнопку на время запроса
        button.prop('disabled', true).text('Добавляем...');
        
        $.ajax({
            url: 'ajax_cart.php',
            type: 'POST',
            data: {
                action: 'add',
                product_id: productId
            },
            dataType: 'json',
            success: function(response) {
    console.log('Add to cart response:', response);
    
    if (response.success) {
        // Обновляем счетчик в шапке
        updateCartCountInHeader(response.cart_count);
        
        // Показываем Bootstrap уведомление
        showBootstrapNotification(response.message || 'Товар добавлен в корзину', 'success');
        
        // Возвращаем текст кнопки через 2 секунды
        setTimeout(function() {
            button.prop('disabled', false).text('Добавить в корзину');
        }, 2000);
        
        // Обновляем корзину в реальном времени
        updateCartDisplay();
    } else {
        showBootstrapNotification(response.message || 'Ошибка при добавлении товара', 'danger');
        button.prop('disabled', false).text('Добавить в корзину');
    }
},
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText, status, error);
                
                // Показываем более подробную ошибку
                var errorMsg = 'Ошибка при добавлении товара в корзину';
                if (xhr.responseText) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMsg = response.message;
                        }
                    } catch(e) {
                        errorMsg = xhr.responseText.substring(0, 100);
                    }
                }
                
                alert(errorMsg);
                button.prop('disabled', false).text('Добавить в корзину');
            }
        });
    });
    
    // Функция обновления отображения корзины (если корзина открыта)
    function updateCartDisplay() {
        // Если мы на странице корзины, обновляем её
        if (window.location.href.indexOf('page=cart') !== -1) {
            $.ajax({
                url: 'ajax_cart.php',
                type: 'POST',
                data: { action: 'get' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateCartView(response);
                    }
                }
            });
        }
    }
    
    // Функция обновления вида корзины
    function updateCartView(data) {
        // Эта функция будет обновлять корзину без перезагрузки
        console.log('Updating cart view with:', data);
    }
    
    function updateCartCountInHeader(count) {
    // Создаем счетчик, если его нет
    if ($('.cart-count').length === 0) {
        $('.logo_icons a[href*="cart"]').append('<span class="cart-count badge bg-danger rounded-pill">' + count + '</span>');
    } else {
        $('.cart-count').text(count);
        if (count > 0) {
            $('.cart-count').show();
        } else {
            $('.cart-count').hide();
        }
    }
}

    // Функция показа уведомления
function showBootstrapNotification(message, type = 'success') {
    // Удаляем предыдущие уведомления
    $('.alert').remove();
    
    var notification = $(
        '<div class="alert alert-' + type + ' alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-5" style="z-index: 2000; min-width: 300px;">' +
            '<div class="d-flex justify-content-between align-items-center">' +
                '<span>' + message + '</span>' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>' +
        '</div>'
    );
    
    $('body').append(notification);
    
    // Автоматически скрываем через 3 секунды
    setTimeout(function() {
        notification.alert('close');
    }, 3000);
}
    
    // Предотвращаем клик на всю карточку товара
    $('.product-card .product-link').on('click', function(e) {
        console.log('Product link clicked');
        // Разрешаем переход по ссылке, но останавливаем всплытие
        return true;
    });
});

$(document).ready(function() {
    var searchTimeout;
    
    // Поиск при вводе текста
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        var searchQuery = $(this).val();
        
        if (searchQuery.length < 2) {
            $('#searchResults').hide();
            return;
        }
        
        searchTimeout = setTimeout(function() {
            $.ajax({
                url: 'search_products.php',
                type: 'POST',
                data: { search: searchQuery },
                success: function(data) {
                    if (data.trim() !== '') {
                        $('#searchResults').html(data).show();
                    } else {
                        $('#searchResults').html('<div class="p-3">Ничего не найдено</div>').show();
                    }
                }
            });
        }, 300);
    });
    
    // Поиск при нажатии кнопки
    $('#searchButton').on('click', function() {
        var searchQuery = $('#search').val();
        if (searchQuery.length > 0) {
            window.location.href = 'index.php?page=catalog&search=' + encodeURIComponent(searchQuery);
        }
    });
    
    // Скрываем результаты при клике вне области
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#search, #searchResults').length) {
            $('#searchResults').hide();
        }
    });
});
</script>

<?=template_footer()?>