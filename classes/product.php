<?php
include_once(__DIR__ . "/Db.php");

class Product {
    private $title;
    private $price;
    private $category;
    private $image; // Nieuwe property voor afbeelding

    // Methode om alle producten op te halen
    public static function getAll() {
        $conn = Db::getConnection();
        $statement = $conn->query("SELECT * FROM products");
        return $statement->fetchAll();
    }
    

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

    public function setCategory($category) {
        $validCategories = ['hond', 'kat', 'knaagdier', 'vogel'];
        if (!in_array($category, $validCategories)) {
            throw new Exception("Invalid category");
        }
        $this->category = $category;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    // Methode om product toe te voegen aan de database
    public function addProduct() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO products (title, price, categorie, image) VALUES (:title, :price, :categorie, :image)");
        $statement->bindValue(':title', $this->title);
        $statement->bindValue(':price', $this->price);
        $statement->bindValue(':categorie', $this->category);
        $statement->bindValue(':image', $this->image);
        return $statement->execute();
    }

    // Methode voor het uploaden van een afbeelding
    public function uploadImage($file) {
        $uploadDir = 'uploads/';

        // Maak de uploads map aan als deze niet bestaat
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadFile = $uploadDir . basename($file['name']);
            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

            // Controleer het bestandstype (optioneel)
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
            }

            // Upload het bestand
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $this->setImage($uploadFile); // Stel het afbeeldingspad in
                return true; // Succes
            } else {
                throw new Exception("Failed to upload image.");
            }
        } else {
            throw new Exception("Image must be uploaded.");
        }
    }

    public static function getByCategory($category) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM products WHERE categorie = :categorie");
        $statement->bindValue(':categorie', $category);
        $statement->execute();
        return $statement->fetchAll();
    }


    public static function searchByName($searchTerm) {
        $searchTerm = "%" . $searchTerm . "%"; // Wildcard for LIKE operator
        
        // SQL query to search products by title
        $query = "SELECT * FROM products WHERE title LIKE :searchTerm";
        $stmt = Db::getConnection()->prepare($query);
        $stmt->bindParam(":searchTerm", $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        
        // Return the result as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteById($id) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = Db::getConnection()->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // Executes the delete query
    }

    public static function update($id, $title, $category, $price, $image) {
        // Verbind met de database
        $db = Db::getConnection();

        // De update query
        $query = "UPDATE products SET title = :title, categorie = :category, price = :price, image = :image WHERE id = :id";
        $stmt = $db->prepare($query);

        // Bind de parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);

        // Voer de query uit
        $stmt->execute();
    }

    public static function getById($id) {
        // Verbind met de database
        $db = Db::getConnection();

        // De query om een product op te halen op basis van de ID
        $query = "SELECT * FROM products WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($query);

        // Bind de parameter
        $stmt->bindParam(':id', $id);

        // Voer de query uit
        $stmt->execute();

        // Haal het product op als een associatief array
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Als het product bestaat, retourneer het
        if ($product) {
            return $product;
        }

        // Als het product niet bestaat, retourneer null
        return null;
    }

}
?>