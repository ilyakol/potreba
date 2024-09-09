<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Potreba\App\classes\Ad;

session_start();

$title = $_POST['title'] ?? null;
$text = $_POST['text'] ?? null;
$price = $_POST['price'] ?? null;
$category = $_POST['category'] ?? null;
$image = $_FILES['image'] ?? null;

$recaptcha_secret = '6Lcf6DUqAAAAAEVT2YEb4L6o_6oj94msowugOgoD';


$recaptcha_response = $_POST['g-recaptcha-response'] ?? null;

try {
    
    if ($recaptcha_response) {
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
        $responseKeys = json_decode($response, true);

        if (intval($responseKeys["success"]) !== 1) {
            throw new \Exception('Помилка reCAPTCHA: ви не пройшли перевірку, спробуйте ще раз.');
        }
    } else {
        throw new \Exception('Помилка reCAPTCHA: відсутня відповідь від reCAPTCHA.');
    }

   
    if ($image['error'] !== UPLOAD_ERR_OK) {
        throw new \Exception("Помилка завантаження файлу: " . $image['error']);
    }

   
    $ad = new Ad($title, $text, $price, $category, $image);
    
    $ad->saveAd();

   
    header("Location: /single_ad.php?id=" . $ad->getId());
    exit;

} catch (\Exception $e) {
    echo "Помилка: " . $e->getMessage();
    echo "<br><a href='/form.php'>Повернутися до форми</a>";
    exit;
}
