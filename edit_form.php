<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Potreba\App\Classes\Category;
use Potreba\App\Classes\Ad;

if (!isset($_GET['id'])) {
    header("Location: /index.php");
    exit;
}

$ad = Ad::fetchById($_GET['id']);
$categories = Category::fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>РЕДАГУВАННЯ</title>
</head>
<body>
    <h1>Редагування оголошення</h1>
    <a href="/index.php"> Список оголошень </a><br><br>
    <form method="post" action="edit_form_handler.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $ad['id']; ?>">
    <p>Введіть назву оголошення</p>
        <input type="text" name="title" placeholder="Назва оголошення" value="<?php echo $ad['title']; ?>" required>
        <p>Введіть текст оголошення</p>
        <textarea name="text" placeholder="Текст оголошення" required><?php echo $ad['text']; ?></textarea>
        <p>Введіть ціну</p>
        <input name="price" type="number" value="<?php echo $ad['price']; ?>">
        <p>Виберіть категорію оголошення</p>
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']) ?>" <?php echo $category['id'] == $ad['category'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <p>Виберіть зображення</p>
        <input type="file" name="image">
        <p><input type="submit" value="Редагувати"></p>
    </form>
</body>
</html>
