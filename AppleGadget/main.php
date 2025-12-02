<?php
$titles = $pdo->prepare('SELECT * FROM gadgets where id = 1'); 
$titles -> execute();
$title = $titles->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo->prepare('SELECT * FROM gadgets order by quantity DESC LIMIT 4');
$stmt -> execute();
$most_popular_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>
<div id="slogan" class="container-fluid bg-dark text-white text-center py-5" style="margin-top: 60px;">
    <h2>iPhone 17 Pro</h2>
    <p>Стильный. Легкий. Прочный. Современный</p>
    <a href="index.php?page=product&id=1" class="btn btn-light btn-lg mt-3">Купить</a>
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
                <?=isset($product['price']) ? number_format((float)$product['price'], 0, '', ' ') : '0'  ?> &#8381;
            </div>
        </a>
        <!-- Вместо формы - кнопка для AJAX -->
        <button class="add-to-cart-btn btn btn-primary w-100 mt-2" data-product-id="<?=$product['id']?>">
            Добавить в корзину
        </button>
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
<script>
$(document).ready(function() {
    // Добавление товара в корзину с главной страницы
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var productId = $(this).data('product-id');
        var button = $(this);
        
        $.ajax({
            url: 'ajax_cart.php',
            type: 'POST',
            data: {
                action: 'add',
                product_id: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Обновляем счетчик в шапке
                    $('.cart-count').text(response.cart_count);
                    
                    // Показываем Bootstrap уведомление
                    showBootstrapNotification('Товар добавлен в корзину', 'success');
                } else {
                    showBootstrapNotification(response.message, 'danger');
                }
            },
            error: function() {
                showBootstrapNotification('Ошибка при добавлении товара', 'danger');
            }
        });
    });
    
    // Функция для Bootstrap уведомлений
    function showBootstrapNotification(message, type) {
        // Создаем элемент уведомления
        var notification = $(
            '<div class="alert alert-' + type + ' alert-dismissible fade show fixed-top mt-5" style="z-index: 2000; margin-left: 20%; margin-right: 20%;">' +
                message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>'
        );
        
        $('body').append(notification);
        
        // Автоматически скрываем через 3 секунды
        setTimeout(function() {
            notification.alert('close');
        }, 3000);
    }
});
</script>
<?=template_footer()?>