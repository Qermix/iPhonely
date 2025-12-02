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
                                <span class="item-total"> <?= isset($item['price']) && isset($item['quantity']) 
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
                
                <button class="checkout-btn" <?= empty($_SESSION['cart']) ? 'disabled' : '' ?>>Перейти к оформлению</button>
            </div>
        </div>
    </div>
</div>
<?=template_footer()?>

<script>
$(document).ready(function() {
    // Обновление счетчика корзины в шапке
    function updateHeaderCart(count) {
        $('.cart-count').text(count);
    }
    
    // Обновление общего количества в корзине
    function updateCartTotals(count, total) {
        $('#cartTotal').text(total.toLocaleString('ru-RU') + ' ₽');
        $('#orderTotal').text(total.toLocaleString('ru-RU') + ' ₽');
        $('.order-row span:first-child').text('Товары (' + count + ')');
        
        // Делаем кнопку оформления активной/неактивной
        if (count === 0) {
            $('.checkout-btn').prop('disabled', true);
        } else {
            $('.checkout-btn').prop('disabled', false);
        }
    }
    
    // Увеличение количества товара (используем делегирование)
    $(document).on('click', '.increase', function() {
        var item = $(this).closest('.cart-item');
        var productId = item.data('product-id');
        var input = item.find('.quantity-input');
        var current = parseInt(input.val()) || 1;
        
        updateCartItem(productId, current + 1, item);
    });
    
    // Уменьшение количества товара (используем делегирование)
    $(document).on('click', '.decrease', function() {
        var item = $(this).closest('.cart-item');
        var productId = item.data('product-id');
        var input = item.find('.quantity-input');
        var current = parseInt(input.val()) || 1;
        
        if (current > 1) {
            updateCartItem(productId, current - 1, item);
        }
    });
    
    // Удаление товара (используем делегирование)
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
                } else {
                    alert(response.message || 'Ошибка при обновлении количества');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText, status, error);
                alert('Ошибка при обновлении количества товара');
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
                    
                    // Обновляем общие данные
                    updateHeaderCart(response.cart_count);
                    updateCartTotals(response.cart_count, response.cart_total);
                    
                    // Если корзина пуста, показываем сообщение
                    if (response.cart_count === 0) {
                        setTimeout(function() {
                            $('#cartItems').html(`
                                <div class="empty-cart">
                                    <h3>Корзина пуста</h3>
                                    <p>Добавьте товары из каталога</p>
                                    <a href="index.php?page=catalog" class="btn-primary">Перейти в каталог</a>
                                </div>
                            `);
                        });
                    }
                } else {
                    alert(response.message || 'Ошибка при удалении товара');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText, status, error);
                alert('Ошибка при удалении товара');
            }
        });
    }
    
    // Обработка кнопки оформления заказа
    $('.checkout-btn').on('click', function() {
        if (!$(this).prop('disabled')) {
            alert('Функция оформления заказа будет реализована позже');
            // window.location.href = 'index.php?page=checkout';
        }
    });
});
</script>