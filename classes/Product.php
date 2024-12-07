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
        // Cloudinary configuratie zonder .env
        $cloudinaryConfig = [
            'cloud' => [
                'cloud_name' => 'dxbez7ob0',   // Vul hier je Cloudinary Cloud Name in
                'api_key'    => '228424447245619',      // Vul hier je Cloudinary API Key in
                'api_secret' => '-O4FBdpNc92q7bEQBZsq_N_lnWE',   // Vul hier je Cloudinary API Secret in
            ]
        ];

        // Initializeer Cloudinary met de configuratie
        $cloudinary = new Cloudinary($cloudinaryConfig);

        // Controleer of er een fout is bij het uploaden van het bestand
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

    // Methode om een product te verwijderen op basis van ID zonder deze error te krijgen: Fatal error: Uncaught PDOException: SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails (`webshop`.`orders`, CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)) in /app/classes/Product.php:183 Stack trace: #0 /app/classes/Product.php(183): PDOStatement->execute() #1 /app/products_admin.php(36): Kocas\Git\Product::deleteById('9') #2 {main} thrown in /app/classes/Product.php on line 183
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
