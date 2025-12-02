<?php
require_once 'function.php';
include 'header.php';
?>

<div class="cart-page">
    <div class="cart-container">
        <div class="cart-main">
            <h1 class="cart-title">Корзина</h1>
            
            <div class="cart-items" id="cartItems">
                <?php if(empty($_SESSION['cart'])): ?>
                    <div class="empty-cart">
                        <h3>Корзина пуста</h3>
                        <p>Добавьте товары из каталога</p>
                        <a href="index.php?page=catalog" class="btn-primary">Перейти в каталог</a>
                    </div>
                <?php else: ?>
                    <?php foreach($_SESSION['cart'] as $item): ?>
                        <div class="cart-item" data-product-id="<?= $item['id'] ?>">
                            <div class="cart-item-image">
                                <img src="img/<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>
                            <div class="cart-item-info">
                                <h3 class="cart-item-name"><?= $item['name'] ?></h3>
                                <p class="cart-item-color"><?= $item['color'] ?></p>
                                <p class="cart-item-price"><?= isset($item['price']) ? number_format((float)$item['price'], 0, '', ' ') : '0' ?> &#8381;</p>
                            </div>
                            <div class="cart-item-quantity">
                                <button class="quantity-btn decrease" type="button">-</button>
                                <input type="number" class="quantity-input" value="<?= $item['quantity'] ?>" min="1" readonly>
                                <button class="quantity-btn increase" type="button">+</button>
                            </div>
                            <div class="cart-item-total">
                                <span class="item-total"><?= isset($item['price']) && isset($item['quantity']) 
                                    ? number_format((float)$item['price'] * (int)$item['quantity'], 0, '', ' ') 
                                    : '0'?> &#8381;</span>
                            </div>
                            <button class="cart-item-remove" type="button">Удалить</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="cart-sidebar">
            <div class="order-summary">
                <h2>Ваш заказ</h2>
                
                <div class="order-row">
                    <span>Товары (<?= getCartCount() ?>)</span>
                    <span id="cartTotal"><?= isset($_SESSION['cart']) && !empty($_SESSION['cart']) 
                        ? number_format(getCartTotal(), 0, '', ' ') 
                        : '0' ?> ₽</span>
                </div>
                
                <div class="order-row">
                    <span>Доставка</span>
                    <span class="free-shipping">0 ₽</span>
                </div>
                
                <div class="shipping-note">
                    <p>Бесплатная доставка при заказе от 5 000 ₽</p>
                </div>
                
                <div class="order-total">
                    <h3>Итого</h3>
                    <h3 id="orderTotal"><?= isset($_SESSION['cart']) && !empty($_SESSION['cart']) 
                        ? number_format(getCartTotal(), 0, '', ' ') 
                        : '0' ?> ₽</h3>
                </div>
                
                <button class="checkout-btn" <?= empty($_SESSION['cart']) ? 'disabled' : '' ?>>
                    <?= empty($_SESSION['cart']) ? 'Корзина пуста' : 'Перейти к оформлению' ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Проверка авторизации (PHP переменная в JS)
    var isLoggedIn = <?= isset($_SESSION['login']) ? 'true' : 'false' ?>;
    
    // Функция для показа уведомлений Bootstrap
    function showBootstrapNotification(message, type = 'success') {
        // Удаляем предыдущие уведомления
        $('.alert-custom').remove();
        
        var alertClass = '';
        switch(type) {
            case 'success': alertClass = 'alert-success'; break;
            case 'danger': alertClass = 'alert-danger'; break;
            case 'warning': alertClass = 'alert-warning'; break;
            case 'info': alertClass = 'alert-info'; break;
            default: alertClass = 'alert-primary';
        }
        
        var notification = $(
            '<div class="alert ' + alertClass + ' alert-custom alert-dismissible fade show position-fixed" style="z-index: 2000; top: 80px; left: 50%; transform: translateX(-50%); min-width: 300px;">' +
                '<div class="d-flex align-items-center">' +
                    '<div class="flex-grow-1">' + message + '</div>' +
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
    
    // Обновление счетчика корзины в шапке
    function updateHeaderCart(count) {
        var cartCountElement = $('.cart-count');
        if (cartCountElement.length === 0) {
            // Создаем счетчик, если его нет
            $('.logo_icons a[href*="cart"]').append('<span class="cart-count badge bg-danger rounded-pill" style="font-size: 10px; padding: 2px 5px;">' + count + '</span>');
        } else {
            cartCountElement.text(count);
            if (count > 0) {
                cartCountElement.show();
            } else {
                cartCountElement.hide();
            }
        }
    }
    
    // Обновление общего количества в корзине
    function updateCartTotals(count, total) {
        $('#cartTotal').text(total.toLocaleString('ru-RU') + ' ₽');
        $('#orderTotal').text(total.toLocaleString('ru-RU') + ' ₽');
        $('.order-row:first-child span:first-child').text('Товары (' + count + ')');
        
        // Делаем кнопку оформления активной/неактивной
        if (count === 0) {
            $('.checkout-btn').prop('disabled', true).text('Корзина пуста');
        } else {
            $('.checkout-btn').prop('disabled', false).text('Перейти к оформлению');
        }
    }
    
    // Увеличение количества товара
    $(document).on('click', '.increase', function() {
        var item = $(this).closest('.cart-item');
        var productId = item.data('product-id');
        var input = item.find('.quantity-input');
        var current = parseInt(input.val()) || 1;
        
        updateCartItem(productId, current + 1, item);
    });
    
    // Уменьшение количества товара
    $(document).on('click', '.decrease', function() {
        var item = $(this).closest('.cart-item');
        var productId = item.data('product-id');
        var input = item.find('.quantity-input');
        var current = parseInt(input.val()) || 1;
        
        if (current > 1) {
            updateCartItem(productId, current - 1, item);
        }
    });
    
    // Удаление товара
    $(document).on('click', '.cart-item-remove', function() {
        var item = $(this).closest('.cart-item');
        var productId = item.data('product-id');
        
        if (confirm('Удалить товар из корзины?')) {
            removeCartItem(productId, item);
        }
    });
    
    // Функция обновления количества товара
    function updateCartItem(productId, quantity, item) {
        $.ajax({
            url: 'ajax_cart.php',
            type: 'POST',
            data: {
                action: 'update',
                product_id: productId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Обновляем количество
                    item.find('.quantity-input').val(quantity);
                    
                    // Обновляем сумму для этого товара
                    if (response.item_total) {
                        item.find('.item-total').text(response.item_total.toLocaleString('ru-RU') + ' ₽');
                    }
                    
                    // Обновляем общие данные
                    updateHeaderCart(response.cart_count);
                    updateCartTotals(response.cart_count, response.cart_total);
                    
                    // Если количество стало 0, удаляем товар
                    if (quantity === 0) {
                        item.remove();
                        showBootstrapNotification('Товар удален из корзины', 'success');
                        
                        // Проверяем, не пуста ли корзина
                        if (response.cart_count === 0) {
                            $('#cartItems').html(`
                                <div class="empty-cart">
                                    <h3>Корзина пуста</h3>
                                    <p>Добавьте товары из каталога</p>
                                    <a href="index.php?page=catalog" class="btn btn-primary">Перейти в каталог</a>
                                </div>
                            `);
                        }
                    }
                } else {
                    showBootstrapNotification(response.message || 'Ошибка при обновлении количества', 'danger');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText, status, error);
                showBootstrapNotification('Ошибка при обновлении количества товара', 'danger');
            }
        });
    }
    
    // Функция удаления товара
    function removeCartItem(productId, item) {
        $.ajax({
            url: 'ajax_cart.php',
            type: 'POST',
            data: {
                action: 'remove',
                product_id: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success){
                    // НЕМЕДЛЕННО УДАЛЯЕМ ЭЛЕМЕНТ ИЗ DOM
                    item.remove();
                    
                    // Обновляем общие данные
                    updateHeaderCart(response.cart_count);
                    updateCartTotals(response.cart_count, response.cart_total);
                    
                    // Показываем уведомление об успешном удалении
                    showBootstrapNotification('Товар удален из корзины', 'success');
                    
                    // Если корзина пуста, показываем сообщение
                    if (response.cart_count === 0) {
                        $('#cartItems').html(`
                            <div class="empty-cart">
                                <h3>Корзина пуста</h3>
                                <p>Добавьте товары из каталога</p>
                                <a href="index.php?page=catalog" class="btn btn-primary">Перейти в каталог</a>
                            </div>
                        `);
                    }
                } else {
                    showBootstrapNotification(response.message || 'Ошибка при удалении товара', 'danger');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText, status, error);
                showBootstrapNotification('Ошибка при удалении товара', 'danger');
            }
        });
    }
    
    // Обработка кнопки оформления заказа - РЕАЛЬНАЯ РЕАЛИЗАЦИЯ
    $('.checkout-btn').on('click', function() {
        var button = $(this);
        if (button.prop('disabled')) return;
        
        // Проверяем авторизацию
        if (!isLoggedIn) {
            showBootstrapNotification('Для оформления заказа необходимо войти в систему', 'warning');
            setTimeout(function() {
                window.location.href = 'index.php?page=login';
            }, 2000);
            return;
        }
        
        // Показываем подтверждение
        if (confirm('Вы уверены, что хотите оформить заказ?')) {
            // Блокируем кнопку
            button.prop('disabled', true).text('Оформляем заказ...');
            
            $.ajax({
                url: 'ajax_order.php',
                type: 'POST',
                data: {
                    action: 'create_order'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Показываем уведомление об успехе
                        showBootstrapNotification(response.message, 'success');
                        
                        // Обновляем счетчик в шапке
                        updateHeaderCart(0);
                        
                        // Очищаем корзину на странице
                        $('#cartItems').html(`
                            <div class="empty-cart">
                                <h3>Заказ успешно оформлен!</h3>
                                <p>Номер вашего заказа: <strong>#${response.order_id}</strong></p>
                                <p>Спасибо за покупку! С вами свяжутся для подтверждения заказа.</p>
                                <a href="index.php?page=catalog" class="btn btn-primary">Продолжить покупки</a>
                            </div>
                        `);
                        
                        // Обновляем общие данные
                        updateCartTotals(0, 0);
                        
                        // Делаем кнопку неактивной
                        button.prop('disabled', true).text('Заказ оформлен');
                        
                        // Через 5 секунд перенаправляем на главную
                        setTimeout(function() {
                            window.location.href = 'index.php?page=home';
                        }, 5000);
                    } else {
                        showBootstrapNotification(response.message, 'danger');
                        button.prop('disabled', false).text('Перейти к оформлению');
                    }
                },
                error: function() {
                    showBootstrapNotification('Ошибка соединения с сервером', 'danger');
                    button.prop('disabled', false).text('Перейти к оформлению');
                }
            });
        }
    });
    
    // Инициализация счетчика в шапке при загрузке страницы
    updateHeaderCart(<?= getCartCount() ?>);
});
</script>

<?=template_footer()?>