<?php

namespace Potreba\App\classes;

class Ad extends AbstractAd
{
    public function __construct($title, $text, $price, $category, $image)
    {
        // Виклик конструктора батьківського класу
        parent::__construct($title, $text, $price, $category, $image);
        
        // Збільшення лічильника при створенні об'єкта
        self::$counter++;
    }

    // Метод для отримання деталей оголошення
    public function getDetails()
    {
        return $this->title . ' - ' . $this->text . ' (Категорія: ' . $this->category . ')';
    }
}
