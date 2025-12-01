<?php

function pdo_connect_mysql(){
    $database_host = 'localhost';
    $database_user = 'root';
    $database_pass = '';
    $database_name = 'applegadget';
try {
    return new PDO('mysql:host='. $database_host . ';dbname=' . $database_name . ';chaaarset=utf8', $database_user, $database_pass);
} catch (PDOException $exception) {
    exit('Ошибка к подключению к базе данных!');
    }
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

