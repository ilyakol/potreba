<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Potreba\App\Classes\Category;

$categories = Category::fetchAll();

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Створення нового оголошення</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <h1>Створення нового оголошення</h1>
    <form method="post" action="form_handler.php" enctype="multipart/form-data" onsubmit="return validateRecaptcha();">
        <p>Введіть назву оголошення</p>
        <input type="text" name="title" placeholder="Назва оголошення" required>
        <p>Введіть текст оголошення</p>
        <textarea name="text" placeholder="Текст оголошення" required></textarea>
        <p>Введіть ціну</p>
        <input name="price" type="number" required>
        <p>Виберіть категорію оголошення</p>
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']) ?>"><?php echo htmlspecialchars($category['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <p>Виберіть зображення</p>
        <input type="file" name="image" required>
        
        
        <div class="g-recaptcha" data-sitekey="6Lcf6DUqAAAAAO8IDJFT6hSC9mn-Re3qorlUsgqu"></div>

        <p><input type="submit" value="Створити"></p>
    </form>

    <script>
    function validateRecaptcha() {
        var response = grecaptcha.getResponse();
        if (response.length === 0) {
            alert("Будь ласка, підтвердьте, що ви не робот.");
            return false; 
        }
        return true; 
    }
    </script>

    <a href="index.php">Переглянути всі оголошення</a><br><br>
</body>
</html>
