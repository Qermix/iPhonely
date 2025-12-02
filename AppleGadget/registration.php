<?php 
if (isset($_SESSION['login'])){
    header('Location: index.php');
    exit;
} 
    include 'header.php';
    $mess = "";
    $mess_type = "";

if(isset($_POST['registr_button'])){
    if(!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['phone'])){
        $login = htmlspecialchars(trim($_POST['login']));
        $password = $_POST['password'];
        $phone = htmlspecialchars(trim($_POST['phone']));
        if(strlen($login) < 3 || strlen($login) > 20){
            $mess = "Логин должен быть от 3 до 20 символов";
            $mess_type = "error";
        }
        elseif(strlen($password) < 5){
            $mess = "Пароль должен быть не менее 5 символов";
            $mess_type = "error";
        }
        else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM customers WHERE login = ?");
            $stmt->execute([$login]);
            $count_of_account = $stmt->fetchColumn();
            if($count_of_account == 0){
                $sql = "INSERT INTO customers (login, password, phone) VALUES (:login, :password, :phone)";
                $query = $pdo->prepare($sql);
                $result = $query->execute([
                    ':login' => $login, 
                    ':password' => $password, 
                    ':phone' => $phone
                ]);
                if($result){
                    $mess = "Регистрация прошла успешно! Теперь вы можете войти в систему.";
                    $mess_type = "success";
                    $_POST['login'] = $_POST['password'] = $_POST['phone'] = '';
                } else { 
                    $mess = "Ошибка при записи данных в базу данных";
                    $mess_type = "error";
                }
            } else { 
                $mess = "Такой логин уже существует!";
                $mess_type = "error";
            }
        }
    } else { 
        $mess = "Все поля обязательны к заполнению!";
        $mess_type = "error";
    }
}
?>
<section class="body">
<div class="form_log">
        <h1 class="title">Создайте аккаунт</h1>
        <p class="under_title">Зарегестрируйтесь для совершения покупок</p>
        <form name="regform" method="post">
            <div class="field">
                <label>Логин</label>
                <input id="login" type="text" placeholder="Придумайте логин" name="login" required>
                <p class="under_input">От 3 до 20 символов (буква, цифры)</p>
            </div>
            <div class="field">
                <label>Пароль</label>
                <input type="password" name="password" required placeholder="Придумайте пароль">
                <p class="under_input">Минимум 5 символов</p>
            </div>
            <div class="field">
                <label>Номер телефона</label>               
                <input id="phone" type="text" name="phone" required placeholder="+7 (949) 428-73-84">
                <p class="under_input">Для подтверждения заказов и уведомлений</p>
            </div>
            <div class="field_checkbox">
                <input id="licens" name="licens" type="checkbox" required required oninvalid="this.setCustomValidity('Необходимо принять пользовательское соглашение!')" oninput="setCustomValidity('')">
                <label>Я согласен с <a href="#">условиями использования</a></label>
            </div>
            <div class="field">
            <input type="submit" name="registr_button" value="Зарегестрироваться">
            </div>
            <div class="signup-link">Уже есть аккаунт? <a href="index.php?page=login">Войти</a></div>
        </form>
</div>
</section>
<?php if(!empty($mess)): ?>
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">
                    <?= $mess_type == 'success' ? '✅ Успешно!' : '⚠️ Ошибка' ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $mess ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<?=template_footer()?>
<script>
$(document).ready(function() {
    var notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
    notificationModal.show();
    
    // При закрытии модального окна перенаправляем на страницу входа, если успешно
    $('#notificationModal').on('hidden.bs.modal', function () {
        <?php if($mess_type == 'success'): ?>
            window.location.href = 'index.php?page=login';
        <?php endif; ?>
    });
});
</script>
<?php endif; ?>