<?php
// ajax_order.php
session_start();
require_once 'function.php';

header('Content-Type: application/json');

// Проверяем авторизацию
if (!isset($_SESSION['login'])) {
    echo json_encode(['success' => false, 'message' => 'Необходимо авторизоваться для оформления заказа']);
    exit;
}

// Проверяем корзину
if (empty($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Корзина пуста']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'create_order') {
    $pdo = pdo_connect_mysql();
    
    try {
        $pdo->beginTransaction();
        
        // Рассчитываем общую сумму
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
        
        // Создаем запись заказа
        $stmt = $pdo->prepare("
            INSERT INTO orders (customer_login, total_amount) 
            VALUES (?, ?)
        ");
        $stmt->execute([$_SESSION['login'], $total_amount]);
        $order_id = $pdo->lastInsertId();
        
        // Добавляем товары заказа
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, quantity, price, color)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $order_id,
                $product_id,
                $item['name'],
                $item['quantity'],
                $item['price'],
                $item['color']
            ]);
        }
        
        $pdo->commit();
        
        // Очищаем корзину после успешного оформления
        $_SESSION['cart'] = [];
        
        echo json_encode([
            'success' => true,
            'message' => 'Заказ #' . $order_id . ' успешно оформлен!',
            'order_id' => $order_id
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Ошибка при оформлении заказа: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
}
?>