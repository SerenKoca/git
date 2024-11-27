<?php
namespace Kocas\Git;

include_once(__DIR__ . '/Db.php');

use Kocas\Git\Db;

class Comment {
    private $text;
    private $productId;
    private $userId;

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
        return $this;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    public function save() {
        try {
            $conn = new \PDO('mysql:host=localhost;dbname=webshop', 'root', ''); // Gebruik de volledig gekwalificeerde \PDO
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $statement = $conn->prepare(
                'INSERT INTO comments (text, productId, userId) VALUES (:text, :productId, :userId)'
            );
            $statement->bindValue(":text", $this->getText());
            $statement->bindValue(":productId", $this->getProductId());
            $statement->bindValue(":userId", $this->getUserId());

            return $statement->execute();
        } catch (\PDOException $e) { // Gebruik de volledig gekwalificeerde \PDOException
            die("Database error: " . $e->getMessage());
        }
    }

    public static function getAll($productId) {
        try {
            $conn = new \PDO('mysql:host=localhost;dbname=webshop', 'root', ''); // Gebruik de volledig gekwalificeerde \PDO
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $statement = $conn->prepare('SELECT * FROM comments WHERE productId = :productId');
            $statement->bindValue(":productId", $productId);
            $statement->execute();

            return $statement->fetchAll(\PDO::FETCH_ASSOC); // Gebruik de volledig gekwalificeerde \PDO::FETCH_ASSOC
        } catch (\PDOException $e) { // Gebruik de volledig gekwalificeerde \PDOException
            die("Database error: " . $e->getMessage());
        }
    }

    

}
