<?php 
    if(isset($_POST['btn']) ){
        $name = htmlspecialchars(trim($_POST['name']));
        $text = htmlspecialchars(trim($_POST['text']));
    $sql = "INSERT INTO message (name, text) VALUES (:name, :text)";
    $query = $pdo->prepare($sql);
                $result = $query->execute([
                    ':name' => $name, 
                    ':text' => $text]);
                    if ($result) $mess = "Сообщение отправлено";
                        else $mess = "Ошибка в отправке";   
    } 
?>
<?=include 'header.php';?>
<h1 class="title_contacts">Связь с нами</h1>
<div class="main_contacts">
    <div class="contacts">
        <div class="block_contact">
            <h2>Наши контакты</h2>
            <div class="contact">
            <i class="fa fa-phone fa-3x" aria-hidden="true"></i>
            <p>+7 (949) 428-73-84</p>
            </div>
            <div class="contact">
            <i class="fa fa-envelope fa-3x" aria-hidden="true"></i>
            <p>info@AppleGadget@mail.ru</p>
            </div>
        </div>
        <div class="block_contact">
            <h2>Наши соцсети</h2>
            <div class="contact">
            <a href="#">
            <i class="fa fa-paper-plane fa-3x" aria-hidden="true"></i>
            <p>Наш телеграмм</p>
            </a>
            </div>
            <div class="contact">
            <a href="#">
            <i class="fa fa-instagram fa-3x" aria-hidden="true"></i>
            <p>Наш *Инстаргам</p>
            </a>
            </div>
        </div>
    </div>
    <div class="form_contacts">
        <h2>Форма для связи</h2>
        <form method="post">
            <input type="text" name="name" placeholder="Введите ваше имя" required><br>
            <input type="textarea" name="text" placeholder="Ваше сообщение" required><br>
            <input type="submit" name="btn" placeholder="Отправить">
        </form>
    </div>
</div>
<?=template_footer()?>
