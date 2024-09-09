<?php

namespace Potreba\App\classes;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Potreba\App\Classes\Category;
use Potreba\App\Classes\Database;
use Potreba\App\interfaces\AdInterface;

abstract class AbstractAd implements AdInterface
{
    protected static $counter = 0;
    
    protected $title;
    protected $text;
    protected $price;
    protected $category;
    protected $image;

    protected $id;
    protected $uploadedImagePath;
    protected $imageIsSaved = false;

    public function __construct($title, $text, $price, $category, $image, $id = null)
    {
        $this->validateInput($title, $text, $price, $category, $image);

        $this->title = $title;
        $this->text = $text;
        $this->price = $price;
        $this->category = $category;
        $this->image = $image;
        $this->id = $id;

        $this->validateInput($title, $text, $price, $category, $image);
        $this->cleanInputFromXSS();
        self::$counter++;
    }

    private function cleanInputFromXSS()
    {
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->text = htmlspecialchars(strip_tags($this->text));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category = htmlspecialchars(strip_tags($this->category));
    }

    public static function getCounter()
    {
        return self::$counter;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getImagename()
    {
        return $this->image['name'];
    }

    protected function validateInput($title, $text, $price, $category, $image)
    {
        if (empty($title)) {
            throw new \Exception("Для створення, напишіть назву");
        }
        if (empty($text)) {
            throw new \Exception("Для створення, напишіть текст оголошення");
        }
        if (empty($category)) {
            throw new \Exception("Для створення, оберіть категорію");
        }
    }

    protected function saveImage()
    {
        if (empty($this->image)) {
            return;  
        }

        // Вказуємо шлях до директорії для завантаження файлів
        $uploads_dir = "C:\potreba\src\public\uploads";
        
        // Перевірка наявності директорії та створення, якщо вона не існує
        if (!file_exists($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }

        if (isset($this->image["error"]) && $this->image["error"] == 0) {
            $tmp_name = $this->image["tmp_name"];
            $name = basename($this->image["name"]);
            $image_path = $uploads_dir . '/' . $name;

            // Зворотній порядок аргументів у move_uploaded_file
            if (move_uploaded_file($tmp_name, $image_path)) {
                $this->uploadedImagePath = $image_path;
                $this->imageIsSaved = true;
            } else {
                throw new \Exception("Не вдалося перемістити завантажений файл");
            }
        } else {
            throw new \Exception("Помилка завантаження файлу");
        }
    }

    public function isImageSaved()
    {
        return $this->imageIsSaved;
    }

    public function saveDb()
    {
        $dbConnection = Database::getInstance()->getConnection();
        
        $stmt = $dbConnection->prepare("INSERT INTO ads (title, text, price, category, image_path) VALUES (?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssss", $this->title, $this->text, $this->price, $this->category, $this->uploadedImagePath);
        
        if ($stmt->execute() === false) {
            throw new \Exception("Помилка збереження оголошення в базі даних: " . $stmt->error);
        }
        
        $this->id = $dbConnection->insert_id;
        $stmt->close();
    }
    
    public function saveAd()
    {
        $this->saveImage();
        $this->saveDb();
    }

    public function getDetails()
    {
        return $this->title . ' - ' . $this->text . '<br/>' . '(Категорія: ' . $this->category . ')';
    }

    public static function fetchAll()
    {
        $dbConnection = Database::getInstance()->getConnection();

        $result = $dbConnection->query("SELECT * FROM ads");

        if ($result === false)  {
            throw new \Exception("Помилка отримання оголошень з бази даних");
        }

        $ads = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($ads as &$ad) {
            $category = Category::fetchById($ad['category']);  
            $ad['category_name'] = !empty($category) ? $category['name'] : null;
        }
        return $ads; 
    }

    public static function fetchById($id)
    {
        $dbConnection = Database::getInstance()->getConnection();
    
        $stmt = $dbConnection->prepare("SELECT * FROM ads WHERE id = ?");
        if ($stmt === false)  {
            throw new \Exception("Помилка підготовки запиту до бази даних");
        }

        $stmt->bind_param('i', $id);
    
        if (!$stmt->execute()) {
            throw new \Exception("Помилка отримання оголошення з бази даних");
        }

        $result = $stmt->get_result();
        $ad = $result->fetch_assoc();
    
        if ($ad === null) {
            throw new \Exception("Оголошення з id = {$id} не знайдено");  
        }
    
        $category = Category::fetchById($ad['category']);
        $ad['category_name'] = !empty($category) ? $category['name'] : null;
    
        return $ad;
    }
    
    public static function deleteById($id)
    {
        $ad = self::fetchById($id);
    
        $dbConnection = Database::getInstance()->getConnection();
    
        $result = $dbConnection->query("DELETE FROM ads WHERE id = $id");
    
        if ($result === false) {
            throw new \Exception("Помилка видалення оголошення з бази даних");
        }
    
        if (file_exists($ad['image_path'])) {
            unlink($ad['image_path']);
        }
    }

    public static function updateById($id, $title, $text, $price, $category, $image)
    {
        $dbConnection = Database::getInstance()->getConnection();

        $stmt = $dbConnection->prepare("UPDATE ads SET title = ?, text = ?, price = ?, category = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $title, $text, $price, $category, $image, $id);

        if ($stmt->execute() === false) {
            throw new \Exception("Помилка оновлення оголошення в базі даних: " . $stmt->error);
        }

        $stmt->close();
    }
}
