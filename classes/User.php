<?php
include_once(__DIR__ . "/Db.php");

class User {
    private $email;
    private $password;

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        if (empty($email)) {
            throw new Exception("Email is verplicht.");
        }
        $this->email = $email;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        if (empty($password)) {
            throw new Exception("Wachtwoord is verplicht.");
        }
        $this->password = $password;
        return $this;
    }

    public function emailExists() {
        // Check if the email already exists in the database
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $statement->bindValue(':email', $this->email);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }

    public function save() {
        if (empty($this->email) || empty($this->password)) {
            throw new Exception("Email en wachtwoord zijn vereist.");
        }

        if ($this->emailExists()) {
            throw new Exception("Email is al geregistreerd.");
        }

        // Encrypt the password before saving
        $options = ['cost' => 12];
        $hash = password_hash($this->password, PASSWORD_DEFAULT, $options);

        $conn = Db::getConnection();
        $statement = $conn->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
        $statement->bindValue(':email', $this->email);
        $statement->bindValue(':password', $hash);

        return $statement->execute();
    }

    public function canLogin($p_email, $p_password) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(':email', $p_email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($p_password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }

    public function isAdmin() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT is_admin FROM users WHERE email = :email");
        $statement->bindValue(':email', $this->email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return isset($result['is_admin']) && $result['is_admin'] == 1;
    }
}
?>