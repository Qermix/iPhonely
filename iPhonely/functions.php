<?php

function pdo_connect_mysql(){
    $database_host = 'localhost';
    $database_user = 'root';
    $database_pass = '';
    $database_name = 'shop';
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
        <div class="footer">
            <p>&copy; $year. Iphonely — все права защищены • Поддержка: support@iphonely.ru</p>
        </div>
    </footer>
</body>
</html>
EOT;
}

?>