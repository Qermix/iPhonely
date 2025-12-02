<?php
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM gadgets WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        exit('Товар не найден!');
    }
    
    // Получаем товары той же категории для "цветов" (если они есть)
    $stmt = $pdo->prepare('SELECT * FROM gadgets WHERE name = ? AND id != ?');
    $stmt->execute([$product['name'], $product['id']]);
    $color_variants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    exit('Товар не найден!');
}

include 'header.php';
?>

<div class="product-content">
    <!-- Хлебные крошки (опционально) -->
    <div class="breadcrumbs">
        <a href="index.php">Главная</a> / 
        <a href="index.php?page=catalog">Каталог</a> / 
        <span><?= htmlspecialchars($product['name']) ?></span>
    </div>
    
    <!-- Основная информация о товаре -->
    <div class="product-main">
        <div class="product_image">
            <img src="img/<?= $product['img'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="500" height="500">
        </div>
        
        <div class="product-details">
            <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
            <p class="product-slogan"><?= htmlspecialchars($product['slogan']) ?></p>
            
            <div class="product-price">
                    <span class="price-old"><?=isset($product['price']) ? number_format((float)$product['price'], 0, '', ' ') : '0'  ?> ₽</span>

            </div>
            
            <!-- Выбор цвета (если есть варианты) -->
           <?php if (!empty($color_variants)): ?>
<div class="color-section mb-4">
    <div class="color-label fw-bold mb-2">Цвет</div>
    <div class="color-options d-flex flex-wrap gap-2">
        <a href="index.php?page=product&id=<?= $product['id'] ?>" 
           class="color-option btn btn-sm <?= ($product['id'] == $_GET['id']) ? 'btn-primary' : 'btn-outline-primary' ?>">
            <?= $product['Color'] ?>
        </a>
        <?php foreach ($color_variants as $variant): ?>
        <a href="index.php?page=product&id=<?= $variant['id'] ?>" 
           class="color-option btn btn-sm <?= ($variant['id'] == $_GET['id']) ? 'btn-primary' : 'btn-outline-primary' ?>">
            <?= $variant['Color'] ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
            
            <!-- Кнопка добавления в корзину -->
            <div class="add-to-cart-section">
   <div class="quantity-selector mb-4">
    <label for="quantity" class="form-label">Количество:</label>
    <div class="d-flex align-items-center" style="width: 150px;">
        <button type="button" class="quantity-btn minus btn btn-outline-secondary rounded-0 rounded-start">-</button>
        <input type="number" id="quantity" class="quantity-input form-control rounded-0 text-center" 
               value="1" min="1" max="<?= $product['quantity'] ?>" style="width: 60px;">
        <button type="button" class="quantity-btn plus btn btn-outline-secondary rounded-0 rounded-end">+</button>
    </div>
</div>
    <button class="add-to-cart-btn product-page-btn" data-product-id="<?= $product['id'] ?>">
        Добавить в корзину
    </button>
</div>
            
            <!-- Информация о товаре -->
            <div class="product_info">
                <div class="info-item">
                    <i class="fa fa-truck" aria-hidden="true"></i>
                    <span>Бесплатная доставка</span>
                </div>
                <div class="info-item">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    <span>В наличии <?= $product['quantity'] ?> шт.</span>
                </div>
                <div class="info-item">
                    <i class="fa fa-shield" aria-hidden="true"></i>
                    <span>Гарантия 1 год</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Описание товара -->
    <div class="product-description mt-5">
    <h2>Описание</h2>
    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
</div>
</div>
<?=template_footer()?>

<script>
$(document).ready(function() {
        // Обновляем основное изображение
        $('#mainProductImage').fadeOut(200, function() {
            $(this).attr('src', imageSrc).fadeIn(200);
        });
    });
$(document).ready(function() {
    // Убираем лишний скрипт (оставляем только один)
    
    // Управление количеством
    $('.quantity-btn.minus').on('click', function() {
        var input = $('#quantity');
        var current = parseInt(input.val());
        if (current > 1) {
            input.val(current - 1);
        }
    });
    
    $('.quantity-btn.plus').on('click', function() {
        var input = $('#quantity');
        var current = parseInt(input.val());
        var max = parseInt(input.attr('max')) || 999;
        if (current < max) {
            input.val(current + 1);
        }
    });
    
    // Добавление товара в корзину
    $('.add-to-cart-btn.product-page-btn').on('click', function(e) {
        e.preventDefault();
        
        var productId = $(this).data('product-id');
        var quantity = parseInt($('#quantity').val()) || 1;
        var button = $(this);
        
        // Блокируем кнопку на время запроса
        button.prop('disabled', true).text('Добавляем...');
        
        $.ajax({
            url: 'ajax_cart.php',
            type: 'POST',
            data: {
                action: 'add',
                product_id: productId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Обновляем счетчик в шапке
                    $('.cart-count').text(response.cart_count);
                    
                    // Bootstrap уведомление
                    showBootstrapNotification(quantity > 1 
                        ? quantity + ' товара добавлено в корзину' 
                        : 'Товар добавлен в корзину', 'success');
                    
                    // Возвращаем текст кнопки
                    setTimeout(function() {
                        button.prop('disabled', false).text('Добавить в корзину');
                    }, 2000);
                } else {
                    showBootstrapNotification(response.message || 'Ошибка при добавлении', 'danger');
                    button.prop('disabled', false).text('Добавить в корзину');
                }
            },
            error: function() {
                showBootstrapNotification('Ошибка при добавлении товара в корзину', 'danger');
                button.prop('disabled', false).text('Добавить в корзину');
            }
        });
    });
    
    // Функция для Bootstrap уведомлений
    function showBootstrapNotification(message, type) {
        var notification = $(
            '<div class="alert alert-' + type + ' alert-dismissible fade show fixed-top mt-5" style="z-index: 2000; margin-left: 20%; margin-right: 20%;">' +
                message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>'
        );
        
        $('body').append(notification);
        
        setTimeout(function() {
            notification.alert('close');
        }, 3000);
    }
});
</script>
</script>
</script>