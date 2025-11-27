<?php
session_start();
if (!isset($_SESSION['login'])){
    header('Location: index.php?page=login');
}
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $stmt = $pdo->prepare('SELECT * FROM product WHERE id = ?');
    $stmt -> execute([$_POST['product_id']]);
    $product = $stmt ->fetch(PDO::FETCH_ASSOC);

    if($product && $quantity > 0){
        if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])){
            if(array_key_exists($product_id, $_SESSION['cart'])){
                $_SESSION['cart'][$product_id] += $quantity;
            }else{
                $_SESSION['cart'][$product_id] = $quantity;
            }
        }else {
            $_SESSION['cart'] = array($product_id => $quantity);
        }
    }
    header('Location: index.php?page=cart');
    exit;
}
if(isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])){
    unset($_SESSION['cart'][$_GET['remove']]);
}
if(isset($_POST['update']) && isset($_SESSION['cart'])){
    foreach($_POST as $k => $v) {
        if(strpos($k, 'quantity') !== false && is_numeric($v)) {
            $id = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            if(is_numeric($id) && isset($_SESSION['cart'][$id]) && $quantity >0) {
                $_SESSION['cart'][$id] = $quantity;
            }
        }
    } header('Location: index.php?page=cart');
    exit;
}
if (isset($_POST['placeorder']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    header('Location: index.php?page=placeorder');
    exit;
}
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;
if($products_in_cart) {
    $array_to_quastions_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    $stmt = $pdo -> prepare('SELECT * FROM product WHERE id IN (' . $array_to_quastions_marks . ')');
    $stmt -> execute(array_keys($products_in_cart));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($products as $product) {
        $subtotal += (float)$product['price'] * (int)$products_in_cart[$product['id']];
    }
}
include 'header.php';
?>
<div class="cart">
    <h1>Корзина</h1>
    <form action="index.php?page=cart" method="post">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Товар</td>
                    <td>Цена</td>
                    <td>Количество</td>
                    <td>Итого</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">В вашей корзине еще нет товаров</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="img">
                            <a href="index.php?page=product&id=<?$product['id']?>">
                                <img src="img/<?=$product['image']?>" alt="<?=$product['title']?>" width="50" height="50">
                            </a>
                        </td>
                        <td>
                            <a href="index.php?page=product&id=<?=$product['id']?>"><?=$product['title']?></a>
                            <br>
                            <a href="index.php?page=cart&remove=<?=$product['id']?>" class="remove">Удалить</a>
                        </td>
                        <td class="price"> <?=$product['price']?>&#8381;</td>
                        <td class="quantity">
                            <input type="number" name="quantity-<?=$product['id']?>" value="<?=$products_in_cart[$product['id']]?>" min="1" max="<?=$product['quantity']?>" placehorder="Quantity" required>
                        </td>
                        <td class="price"><?=$product['price'] * $products_in_cart[$product['id']]?>&#8381;</td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Итого</span>
            <span class="price"><?=$subtotal?>&#8381;</span>
        </div>
        <div class="buttons">
            <input type="submit" value="Обновить" name="update">
            <input type="submit" value="Заказать" name="placeorder">
        </div>
    </form>
</div>
<?=template_footer()?>