<?php

use Potreba\App\classes\Ad;

ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';


if (!isset($_GET['id'])) {
    header("Location: /index.php");
    exit('Помилка: id не вказано');
}

try {
    Ad::deleteById($_GET['id']);
} catch (\Exception $e) {
    echo "Помилка: " . $e->getMessage();
    echo "<br><a href='/form.html'>Повернутися до форми</a>";
    exit;
}


header("Location: /index.php");
exit;
?>
<a href="index.php">Переглянути всі оголошення</a><br><br>
<a href="form.php">Створити нове оголошення</a>