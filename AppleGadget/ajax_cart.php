<?php
// ajax_cart.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'function.php';

// Создаем подключение к БД
$pdo = pdo_connect_mysql();

// Проверяем подключение к БД
if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Ошибка подключения к базе данных']);
    exit;
}

header('Content-Type: application/json');

// Получаем действие
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Функция для отладки
function debug_log($message) {
    error_log("AJAX_CART: " . $message);
}

debug_log("Action received: " . $action);

switch ($action) {
    case 'add':
        addToCart($pdo);
        break;
    case 'update':
        updateCart($pdo);
        break;
    case 'clear':
        clearCart();
        break;
    case 'remove':
        removeFromCart($pdo);
        break;
    case 'get':
        getCartData($pdo);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Неизвестное действие: ' . $action]);
        exit;
}

// Добавляем новую функцию:
function clearCart() {
    $_SESSION['cart'] = [];
    
    echo json_encode([
        'success' => true,
        'message' => 'Корзина очищена'
    ]);
}

function addToCart($pdo) {
    // Проверяем, инициализирована ли корзина
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (!isset($_POST['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Не указан ID товара']);
        return;
    }
    
    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Получаем информацию о товаре из БД
    $stmt = $pdo->prepare("SELECT * FROM gadgets WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Товар не найден']);
        return;
    }
    
    // Преобразуем цену в число
    $price = (float)str_replace([' ', '₽', 'руб.'], '', $product['price']);
    
    // Проверяем, есть ли товар уже в корзине
    if (isset($_SESSION['cart'][$product_id])) {
        // Увеличиваем количество
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Добавляем новый товар
        $_SESSION['cart'][$product_id] = [
            'id' => $product_id,
            'name' => $product['name'],
            'price' => $price,
            'quantity' => $quantity,
            'color' => isset($product['Color']) ? $product['Color'] : $product['color'],
            'img' => $product['img'],
            'slogan' => $product['slogan']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'cart_count' => getCartCount(),
        'cart_total' => getCartTotal(),
        'message' => 'Товар "' . $product['name'] . '" добавлен в корзину'
    ]);
}

function updateCart($pdo) {
    debug_log("updateCart called");
    
    if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
        echo json_encode(['success' => false, 'message' => 'Не указаны параметры']);
        return;
    }
    
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    debug_log("Update product " . $product_id . " to quantity " . $quantity);
    
    if (!isset($_SESSION['cart'][$product_id])) {
        echo json_encode(['success' => false, 'message' => 'Товар не найден в корзине']);
        return;
    }
    
    if ($quantity < 1) {
        // Удаляем товар, если количество меньше 1
        unset($_SESSION['cart'][$product_id]);
        $item_total = 0;
    } else {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        $price = (float)$_SESSION['cart'][$product_id]['price'];
        $item_total = $price * $quantity;
    }
    
    require_once 'function.php';
    
    echo json_encode([
        'success' => true,
        'cart_count' => getCartCount(),
        'cart_total' => getCartTotal(),
        'item_total' => $item_total
    ]);
}

function removeFromCart($pdo) {
    debug_log("removeFromCart called");
    
    if (!isset($_POST['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Не указан ID товара']);
        return;
    }
    
    $product_id = (int)$_POST['product_id'];
    
    debug_log("Remove product " . $product_id);
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    
    require_once 'function.php';
    
    echo json_encode([
        'success' => true,
        'cart_count' => getCartCount(),
        'cart_total' => getCartTotal()
    ]);
}

function getCartData($pdo) {
    debug_log("getCartData called");
    
    require_once 'function.php';
    
    echo json_encode([
        'success' => true,
        'cart' => isset($_SESSION['cart']) ? $_SESSION['cart'] : [],
        'cart_count' => getCartCount(),
        'cart_total' => getCartTotal()
    ]);
}
?>