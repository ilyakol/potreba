<?php

namespace Potreba\App\Classes;

class Category
{
    public static function fetchAll()
    {
        $db = Database::getInstance()->getConnection();

        $result = $db->query("SELECT * FROM categories");

        return $result->fetch_all(MYSQLI_ASSOC);
    }



public static function fetchById($id)
{
    $db = Database::getInstance();

    $result = $db->getConnection()->query("SELECT * FROM categories WHERE id = {$id}");

    return $result->fetch_assoc();
}
}