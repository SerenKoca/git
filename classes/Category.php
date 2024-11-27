<?php

namespace Kocas\Git;

include_once(__DIR__ . '/Db.php');

use Kocas\Git\Db;

class Category {
    // Voeg een nieuwe categorie toe (met icoon)
    public static function addCategory($name, $icon) {
        // Verbind met de database
        $db = Db::getConnection();
        
        // Voeg de categorie en het icoon toe
        $stmt = $db->prepare("INSERT INTO categories (name, icon) VALUES (:name, :icon)");
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':icon', $icon);
        $stmt->execute();
    }

    // Haal alle categorieën op
    public static function getAllCategories() {
        $db = Db::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
