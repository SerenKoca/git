<?php

namespace Web\XD;
use Web\XD\Db;

include_once(__DIR__ . "/Db.php");

class Product {
    private $title;
    private $price;
    private $category;
    private $image;
    private $description; // Nieuwe eigenschap voor beschrijving

    // Setter voor titel
    public function setTitle($title) {
        if (empty($title)) {
            throw new Exception("Title cannot be empty");
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

    // Setter voor categorie
    public function setCategory($category) {
        $validCategories = ['hond', 'kat', 'knaagdier', 'vogel'];
        if (!in_array($category, $validCategories)) {
            throw new Exception("Invalid category");
        }
        $this->category = $category;
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
    }

    // Methode om product toe te voegen aan de database
    public function addProduct() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO products (title, price, categorie, image, description) VALUES (:title, :price, :categorie, :image, :description)");
        $statement->bindValue(':title', $this->title);
        $statement->bindValue(':price', $this->price);
        $statement->bindValue(':categorie', $this->category);
        $statement->bindValue(':image', $this->image);
        $statement->bindValue(':description', $this->description); // Bind de beschrijving aan de query
        return $statement->execute();
    }

    // Methode voor het uploaden van een afbeelding
    public function uploadImage($file) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadFile = $uploadDir . basename($file['name']);
            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
            }
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $this->setImage($uploadFile);
                return true;
            } else {
                throw new Exception("Failed to upload image.");
            }
        } else {
            throw new Exception("Image must be uploaded.");
        }
    }

    // Methode om alle producten op te halen
    public static function getAll() {
        $conn = Db::getConnection(); // Zorg ervoor dat je Db::getConnection() gebruikt
        $statement = $conn->prepare("SELECT * FROM products LIMIT 10");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC); // Voeg de backslash toe voor de ingebouwde PDO
    }
    // Methode om producten op te halen op basis van categorie
    public static function getByCategory($category) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM products WHERE categorie = :categorie");
        $statement->bindValue(':categorie', $category);
        $statement->execute();
        return $statement->fetchAll();
    }

    // Methode om producten te zoeken op naam
    public static function searchByName($searchTerm) {
        $searchTerm = "%" . $searchTerm . "%";
        $query = "SELECT * FROM products WHERE title LIKE :searchTerm OR description LIKE :searchTerm";
        $stmt = Db::getConnection()->prepare($query);
        $stmt->bindParam(":searchTerm", $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Methode om een product te verwijderen op basis van ID
    public static function deleteById($id) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = Db::getConnection()->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT); // Voeg de backslash toe voor de ingebouwde PDO
        return $stmt->execute();
    }

    // Methode om een product bij te werken
    public static function update($id, $title, $category, $price, $image, $description) {
        $db = Db::getConnection();
        $query = "UPDATE products SET title = :title, categorie = :category, price = :price, image = :image, description = :description WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':description', $description);
        return $stmt->execute(); // Voer de statement uit
    }

    // Methode om een product op te halen op basis van ID
    public static function getById($id) {
        $conn = Db::getConnection();
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT); // Voeg de backslash toe voor de ingebouwde PDO
        $stmt->execute();
        
        // Haal productdetails op, retourneer null als er geen product gevonden wordt
        $product = $stmt->fetch(\PDO::FETCH_ASSOC); // Voeg de backslash toe voor de ingebouwde PDO
        return $product ? $product : null;
    }

}