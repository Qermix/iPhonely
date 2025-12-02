<?php
if (isset($_SESSION['login'])){
    header('Location: index.php');
} if(isset($_POST['login'])){
    if((!empty ($_POST["login"])) && !empty ($_POST['password'])){
        $login = htmlspecialchars($_POST['login']);
        $password = htmlspecialchars($_POST['password']);
        $sql = ("SELECT * FROM customers WHERE login = '$login' AND password = '$password'");
        $quary = $pdo -> prepare($sql);
        $quary -> execute();
        $result = $pdo -> query($sql);
        $column_in_db = $quary -> fetchColumn();
        if($column_in_db != 0){
            while ($rows = $result -> fetch(PDO::FETCH_ASSOC)) {
                echo $rows['login'];
                $dblogin = $rows['login'];
                $dbpassowrd = $rows['password'];
            }if ($login == $dblogin && $password == $dbpassowrd){
                $_SESSION['login'] = $login;
                header('Location: index.php');
            }
        } else { $mess="Неверный логин или пароль!" ; }
    } else { $mess ="Все поля обязательны для заполнения"; }
}
include 'header.php';
?>
<section class="body">
    <div class="form_log">
        <div class="title">Войдите в акаунт</div>
        <p class="under_title">Введите логин и пароль для входа</p>
        <form method="post">
            <div class="field">
                <label >Номер телефона/Email</label>
                <input type="text" name="login" required>
            </div>
            <div class="field">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            <div class="logins field">
                <input type="submit" name="button_login" value="Войти">
            </div>
            <div class="signup-link"> Еще нет аккаунта? <a href="index.php?page=registration">Зарегестрироваться</a></div>
        </form>
    </div>
</section>
<?php if(!empty($mess)){ echo "<div class='error'>" . $mess . "</div>"; } ?>
<?=template_footer();?>