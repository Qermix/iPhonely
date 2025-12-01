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
<div id="notificationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-icon">
            <span id="modalIcon"></span>
        </div>
        <div id="modalMessage"><?=$mess?></div>
        <div class="modal-buttons">
            <button id="modalOkButton" class="modal-btn">OK</button>
        </div>
    </div>
</div>
<?=template_footer()?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('notificationModal');
    const modalMessage = document.getElementById('modalMessage');
    const modalIcon = document.getElementById('modalIcon');
    const closeBtn = document.querySelector('.close');
    const okButton = document.getElementById('modalOkButton');

    function showModal(message, type) {
        modalMessage.innerHTML = message;

        switch(type) {
            case 'success':
                modalIcon.textContent = '✓';
                modalIcon.style.background = '#4CAF50';
                break;
            case 'error':
                modalIcon.textContent = '!';
                modalIcon.style.background = '#f44336';
                break;
            default:
                modalIcon.textContent = 'i';
                modalIcon.style.background = '#2196F3';
        }
        
        modal.style.display = 'block';
        if(type === 'success') {
            setTimeout(function() {
                modal.style.display = 'none';
                window.location.href = 'index.php?page=login';
            }, 3000);
        }
    }
    
    function closeModal() {
        modal.style.display = 'none';
    }
    
    closeBtn.onclick = closeModal;
    okButton.onclick = closeModal;
    
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
    
    // Показываем модальное окно, если есть сообщение
    <?php if(!empty($mess)): ?>
        showModal("<?php echo $mess; ?>", "<?php echo $mess_type; ?>");
    <?php endif; ?>
});
</script>