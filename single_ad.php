<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Potreba\App\classes\Ad;
use Potreba\App\Classes\Category;


if (!isset($_GET['id'])) {
    header("Location: /index.php");
    exit('Помилка: id не вказано');
}

$ad = Ad::fetchById($_GET['id']);

$title = $_SESSION['title'] ?? null;
$text = $_SESSION['text'] ?? null;
$price = $_SESSION['price'] ?? null;
$category = $_SESSION['category'] ?? null;
$image = $_SESSION['image_path'] ?? null;

echo "<h1> Ваше оголошення </h1>";
echo "<a href='/delete_ad.php?id=" . $ad['id'] . "'> Видалити оголошення</a><br><br>";
echo "<a href='/edit_form.php?id=" . $ad['id'] . "'> РЕДАГУВАТИ</a><br><br>";
echo "Назва оголошення: " .$ad['title'] . "<br>"; 
echo "Текст оголошення: " . $ad['text'] . "<br>";
echo "Ціна оголошення: " . $ad['price'] . "<br>";
echo "Категорія оголошення: " . $ad['category_name'] . "<br>";
echo "Зображення оголошення:  <img src= '" . $ad['image_path'] ."'/><br>";

?>
<br>
<a href="index.php">Переглянути всі оголошення</a><br><br>
<a href="form.php">Створити нове оголошення</a>
