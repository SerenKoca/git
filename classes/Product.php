<?php

namespace Kocas\Git;

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
        // Haal de Cloudinary-configuratie op uit de database
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT cloud_name, api_key, api_secret FROM config LIMIT 1");
        $stmt->execute();
        $config = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$config) {
            throw new \Exception("Cloudinary configuration not found in the database.");
        }

        $cloudinaryConfig = [
            'cloud' => [
                'cloud_name' => $config['cloud_name'],
                'api_key'    => $config['api_key'],
                'api_secret' => $config['api_secret'],
            ]
        ];

        // Initialiseer Cloudinary
        $cloudinary = new Cloudinary($cloudinaryConfig);

        // Controleer of het bestand geüpload kan worden
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("File upload error: " . $file['error']);
        }

        try {
            // Upload bestand naar Cloudinary
            $result = $cloudinary->uploadApi()->upload($file['tmp_name'], [
                'folder' => 'webshop_images',
            ]);

            // Sla de URL van de geüploade afbeelding op
            $this->setImage($result['secure_url']);
            return true;
        } catch (Exception $e) {
            throw new \Exception("Failed to upload image to Cloudinary: " . $e->getMessage());
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

    public static function getAllIndex() {
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
        
        // haal de categorie-ID op op basis van de categorienaam
        $stmt = $conn->prepare("SELECT id FROM categories WHERE name = :category_name LIMIT 1");
        $stmt->bindValue(':category_name', $categoryName, \PDO::PARAM_STR);
        $stmt->execute();
        $category = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($category) {
            $categoryId = $category['id'];
            
            // Haal de producten op op basis van de categorie-ID
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
            return []; // als geen categorie gevonden is, geef een lege array terug
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
    
        // Verwijder gerelateerde orders
        $deleteOrdersStatement = $conn->prepare("DELETE FROM orders WHERE product_id = :id");
        $deleteOrdersStatement->bindValue(':id', $id, \PDO::PARAM_INT);
        $deleteOrdersStatement->execute();

        // Verwijder gerelateerde comments
        $deleteCommentsStatement = $conn->prepare("DELETE FROM comments WHERE productId = :id");
        $deleteCommentsStatement->bindValue(':id', $id, \PDO::PARAM_INT);
        $deleteCommentsStatement->execute();
    
        // Verwijder het product
        $statement = $conn->prepare("DELETE FROM products WHERE id = :id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        return $statement->execute();
    }
    

    // Methode om een product bij te werken
    public static function update($productId, $title, $category, $price, $imageUrl, $description) {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("UPDATE products SET title = :title, category_id = :category, price = :price, image = :image, description = :description WHERE id = :id");
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':image', $imageUrl);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':id', $productId);
        $stmt->execute();
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
