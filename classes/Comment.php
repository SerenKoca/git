<?php

include_once(__DIR__ . "/Db.php");

// Comment.php

class Comment {
    private $text;
    private $postId;
    private $userId;

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    public function getPostId() {
        return $this->postId;
    }

    public function setPostId($postId) {
        $this->postId = $postId;
        return $this;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    // Save the comment to the database
    public function save() {
        $conn = new PDO('mysql:host=localhost;dbname=webshop', 'root', '');
        $statement = $conn->prepare('INSERT INTO comments (text, postId, userId) VALUES (:text, :postId, :userId)');

        $text = $this->getText();
        $postId = $this->getPostId();
        $userId = $this->getUserId();

        $statement->bindValue(":text", $text);
        $statement->bindValue(":postId", $postId);
        $statement->bindValue(":userId", $userId);

        return $statement->execute();
    }

    // Get all comments for a product (postId)
    public static function getAll($postId) {
        $conn = new PDO('mysql:host=localhost;dbname=webshop', 'root', '');
        $statement = $conn->prepare('
            SELECT comments.*, users.email AS username 
            FROM comments 
            JOIN users ON comments.userId = users.id 
            WHERE postId = :postId
        ');
        $statement->bindValue(":postId", $postId);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>
