<?php
// Запускаем сессию в самом начале
session_start();

// Инициализируем корзину, если её нет
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function pdo_connect_mysql(){
    $database_host = 'localhost';
    $database_user = 'root';
    $database_pass = '';
    $database_name = 'applegadget';
    try {
        return new PDO('mysql:host='. $database_host . ';dbname=' . $database_name . ';charset=utf8', $database_user, $database_pass);
    } catch (PDOException $exception) {
        exit('Ошибка к подключению к базе данных!');
    }
}

// Функция для получения общего количества товаров в корзине
function getCartCount() {
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    return $count;
}

// Функция для получения общей суммы корзины
// Функция для получения общей суммы корзины
function getCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            // Проверяем, что price и quantity существуют и являются числами
            if (isset($item['price']) && isset($item['quantity'])) {
                $price = (float)$item['price'];
                $quantity = (int)$item['quantity'];
                $total += $price * $quantity;
            }
        }
    }
    return $total;
}

function template_footer(){
    $year= date('Y');
    echo <<<EOT
        </main>
    <footer>
        <div class="info_footer">
            <div class="foot">
                <h3>AppleGadget</h3>
                <p>Оригинальная техника Apple c гарантией и доставкой по всей России</p>
            </div>
            <div class="foot">
                <h3>Контакты</h3>
                   <p>+7 (949) 123-45-67
                    info@applegadget@mail.ru</p>
            </div>
            <div class="foot">
                <h3>Доставка</h3>
                    <p>По Донецку &#8776; 2 часа <br>
                    По России &#8776; 2-4 дня</p>
            </div>
            <div class="foot">
                <h3>Гарантия</h3>
                    <p>Официальная гарантия 1 год
                    Обмен и возврат 14 дней</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; AppleGadget, $year. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>
EOT;
}
?>

