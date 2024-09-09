<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Potreba\App\classes\Ad;

session_start();

// Отримання даних із форми
$id = $_POST['id'] ?? null;
$title = $_POST['title'] ?? null;
$text = $_POST['text'] ?? null;
$price = $_POST['price'] ?? null;
$category = $_POST['category'] ?? null;
$image = $_FILES['image'] ?? null;

try {
    // Перевірка, чи всі необхідні дані отримані
    if (!$id || !$title || !$text || !$category) {
        throw new \Exception("Не всі необхідні дані були надані.");
    }

    // Отримання оголошення за його ID
    $ad = Ad::fetchById($id);

    if (!$ad) {
        throw new \Exception("Оголошення з таким ID не знайдено.");
    }

    // Оновлення зображення, якщо було завантажено нове
    $uploadedImagePath = $ad['image_path'];
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $uploads_dir = "uploads";
        if (!file_exists($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }

        $tmp_name = $image["tmp_name"];
        $name = basename($image["name"]);
        $uploadedImagePath = $uploads_dir . '/' . $name;

        if (!move_uploaded_file($tmp_name, $uploadedImagePath)) {
            throw new \Exception("Не вдалося перемістити завантажений файл");
        }
    }

    // Оновлення оголошення в базі даних
    Ad::updateById($id, $title, $text, $price, $category, $uploadedImagePath);

    // Переадресація на сторінку оголошення після успішного оновлення
    header("Location: /single_ad.php?id=" . $id);
    exit;

} catch (\Exception $e) {
    echo "Помилка: " . $e->getMessage();
    echo "<br><a href='/edit_form.php?id=" . htmlspecialchars($id) . "'>Повернутися до форми</a>";
    exit;
}
