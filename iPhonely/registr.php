<?php 
if (isset($_SESSION['login'])){
    header('Location: index.php');
} if(isset($_POST['registr_button'])){
        if(!empty($_POST['login']) && (!empty($_POST['password']))){
            $login = htmlspecialchars($_POST['login']);
            $password = htmlspecialchars($_POST['password']);
            $stmt = $pdo -> prepare("SELECT * FROM customers WHERE login= '$login'");
            $stmt -> execute();
            $count_of_account = $stmt -> fetchColumn();
            if($count_of_account == 0){
                $sql = ("INSERT INTO customers (login, password) values (:login, :password)");
                $quary = $pdo -> prepare($sql);
                $quary -> execute([':login'=>$login, ':password'=>$password]);
                if($sql==true){
                    $mess = "Регистрация прошла успешно";
                } else { $mess = "Ошибка при записи данных в базу данных";}
            }else{ $mess = "Такой логин уже существует!";}
        }else { $mess = "Все поля обязательны к заполнению!";}
    }
    include 'header.php';
?>
<section class="body">
<div class="form_log">
        <div class="title">Регистрация</div>
        <form name="regform" method="post">
            <div class="field">
                <input id="login" type="text" name="login" required>
                <label>Логин</label>
            </div>
            <div class="field">
                <input type="password" name="password" required>
                <label>Пароль</label>
            </div>
            <div class="field">
            <input type="submit" name="registr_button" value="Зарегестрироваться">
            </div>
            <div class="signup-link">Уже есть аккаунт? <a href="index.php?page=login">Войти</a></div>
        </form>
</div>
</section>
<?php if(!empty($mess)){ echo "<div class='error'>" . $mess . "</div>"; } ?>
<?=template_footer()?>