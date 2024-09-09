<?php

use Potreba\App\classes\Ad;

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

$ads = Ad::fetchAll();
?> 
<html> 
<head>
    <title>Оголошення</title>
</head>
<body>
    <h1>Оголошення</h1>
    <a href="/form.php">Створити оголошення</a>
    <br>
    <br>
    <table style="width: 80%;" border="1">
        <thead>
            <tr>
                <th style="width: 30%;">ID</th>
                <th style="width: 30%;">Назва</th>
                <th style="width: 30%;">Текст</th>
                <th style="width: 30%;">Ціна</th>
                <th style="width: 30%;">Категорія</th>
                <th style="width: 10%;">Дата створення</th>
                <th style="width: 10%;">Дії</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach($ads as $ad) { ?>
            <tr>
                <td><?php echo $ad['id']; ?></td>
                <td><?php echo $ad['title']; ?></td>
                <td><?php echo $ad['text']; ?></td>
                <td><?php echo $ad['price']??'----'; ?></td>
                <td><?php echo $ad['category_name']; ?></td>
                <td><?php echo $ad['created_at']; ?></td>
                <td><a href="single_ad.php?id=<?php echo $ad['id'];?>">Переглянути</a></td>

            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>