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
            $conn = Db::getConnection();
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $statement = $conn->prepare(
                'INSERT INTO comments (text, productId, userId) VALUES (:text, :productId, :userId)'
            );
            $statement->bindValue(":text", $this->getText());
            $statement->bindValue(":productId", $this->getProductId());
            $statement->bindValue(":userId", $this->getUserId());

            if ($statement->execute()) {
                error_log("Comment succesvol opgeslagen: " . $this->getText());
                return true;
            } else {
                error_log("Comment opslaan mislukt.");
                return false;
            }
        } catch (\PDOException $e) {
            error_log("Database error bij opslaan: " . $e->getMessage());
            die("Database error: " . $e->getMessage());
        }
    }

    public static function getAll($productId) {
    try {
        $conn = Db::getConnection();
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Query om alleen reacties op te halen voor het specifieke productId
        $statement = $conn->prepare('SELECT * FROM comments WHERE productId = :productId ORDER BY created_at DESC');
        $statement->bindValue(":productId", $productId, \PDO::PARAM_INT);
        $statement->execute();

        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
        error_log("Aantal opgehaalde reacties voor productId $productId: " . count($results));
        return $results;
    } catch (\PDOException $e) {
        error_log("Database error bij ophalen van reacties: " . $e->getMessage());
        die("Database error: " . $e->getMessage());
    }
}

}
