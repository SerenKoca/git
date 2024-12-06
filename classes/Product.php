<?php

namespace Kocas\Git;

include_once(__DIR__ . '/Db.php');
include_once(__DIR__ . '/../vendor/autoload.php'); // Voeg deze regel toe voor Composer autoload (indien gebruikt)

use Cloudinary\Cloudinary; // Voeg deze regel toe om de Cloudinary-klasse te gebruiken
use Kocas\Git\Db;

class Product {
    private $title;
    private $price;
    private $categoryId;
    private $image;
    private $description;

    // Setter voor titel
    public function setTitle($title) {
        if (empty($title)) {
            throw new \Exception("Title cannot be empty");
        }
        $this->title = htmlspecialchars($title);
        return $this;
    }

    // Setter voor prijs
    public function setPrice($price) {
        if (!is_numeric($price) || $price <= 0) {
            throw new Exception("Price must be a positive number");
        }
        $this->price = $price;
        return $this;
    }

    // Setter voor categorie (verwacht ID)
    public function setCategoryId($categoryId) {
        if (!is_numeric($categoryId) || $categoryId <= 0) {
            throw new Exception("Invalid category ID");
        }
        $this->categoryId = $categoryId;
        return $this;
    }

    // Setter voor beschrijving
    public function setDescription($description) {
        if (empty($description)) {
            throw new Exception("Description cannot be empty");
        }
        $this->description = htmlspecialchars($description);
        return $this;
    }

    // Setter voor afbeelding
    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    // Methode om product toe te voegen aan de database
       public function addProduct() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("
            INSERT INTO products (title, price, category_id, image, description) 
            VALUES (:title, :price, :category_id, :image, :description)
        ");
        $statement->bindValue(':title', $this->title);
        $statement->bindValue(':price', $this->price);
        $statement->bindValue(':category_id', $this->categoryId);
        $statement->bindValue(':image', $this->image);
        $statement->bindValue(':description', $this->description);
        return $statement->execute();
    }


    // Methode voor het uploaden van een afbeelding
    public function uploadImage($file) {
        // Cloudinary configuratie inladen
        $configFilePath = __DIR__ . '/../config/cloudinary.php'; // pad naar je cloudinary.php bestand

        if (file_exists($configFilePath)) {
            $config = include($configFilePath);
        } else {
            throw new \Exception("Cloudinary configuration file not found.");
        }

        // Initializeer Cloudinary
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $config['webshop'],
                'api_key'    => $config['api_key'],
                'api_secret' => $config['api_secret'],
            ]
        ]);

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error: " . $file['error']);
        }

        try {
            // Upload bestand naar Cloudinary
            $result = $cloudinary->uploadApi()->upload($file['tmp_name'], [
                'folder' => 'webshop_images', // Optioneel: Organiseer bestanden in een map
            ]);

            // Sla de URL van de geüploade afbeelding op
            $this->setImage($result['secure_url']);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to upload image to Cloudinary: " . $e->getMessage());
        }
    }

    // Methode om alle producten op te halen
    public static function getAll() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("
            SELECT products.*, categories.name AS category_name 
            FROM products 
            LEFT JOIN categories ON products.category_id = categories.id
        ");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Methode om alle categorieën op te halen
    public static function getCategories() {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Methode om producten op te halen op basis van categorie-ID
    public static function getByCategory($categoryName) {
        $conn = Db::getConnection();
        
        // First, get the category ID based on the category name
        $stmt = $conn->prepare("SELECT id FROM categories WHERE name = :category_name LIMIT 1");
        $stmt->bindValue(':category_name', $categoryName, \PDO::PARAM_STR);
        $stmt->execute();
        $category = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($category) {
            $categoryId = $category['id'];
            
            // Now, fetch the products by category ID
            $statement = $conn->prepare("
                SELECT products.*, categories.name AS category_name 
                FROM products 
                LEFT JOIN categories ON products.category_id = categories.id 
                WHERE category_id = :category_id
            ");
            $statement->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return []; // If no category found, return empty
        }
    }

    // Methode om producten te zoeken op titel of beschrijving
    public static function searchByName($searchTerm) {
        $conn = Db::getConnection();
        $searchTerm = "%" . $searchTerm . "%";
        $statement = $conn->prepare("
            SELECT products.*, categories.name AS category_name 
            FROM products 
            LEFT JOIN categories ON products.category_id = categories.id 
            WHERE title LIKE :searchTerm OR description LIKE :searchTerm
        ");
        $statement->bindValue(":searchTerm", $searchTerm, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Methode om een product te verwijderen op basis van ID
    public static function deleteById($id) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("DELETE FROM products WHERE id = :id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        return $statement->execute();
    }

    // Methode om een product bij te werken
    public static function update($id, $title, $categoryId, $price, $image, $description) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("
            UPDATE products 
            SET title = :title, category_id = :category_id, price = :price, image = :image, description = :description 
            WHERE id = :id
        ");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':image', $image);
        $statement->bindValue(':description', $description);
        return $statement->execute();
    }

    // Methode om een product op te halen op basis van ID
    public static function getById($id) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("
            SELECT products.*, categories.name AS category_name 
            FROM products 
            LEFT JOIN categories ON products.category_id = categories.id 
            WHERE products.id = :id
        ");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    
}
