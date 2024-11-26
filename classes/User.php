<?php
namespace Kocas\Git;

include_once(__DIR__ . '\Db.php');

use Kocas\Git\Db;


 

class User {
    protected $email;
    protected $password;

    public function __construct($email = null, $password = null) {
        if ($email) {
            $this->email = $email;
        }
        if ($password) {
            $this->password = $password;
        }
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        if (empty($email)) {
            echo ("Email is verplicht.");
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
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $statement->bindValue(':email', $this->email);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }

    public function canLogin($p_email, $p_password) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(':email', $p_email);
        $statement->execute();
        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($p_password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword($currentPassword, $newPassword) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT password FROM users WHERE email = :email");
        $statement->bindValue(':email', $this->email);
        $statement->execute();
        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            throw new Exception("Huidige wachtwoord is onjuist.");
        }

        if (strlen($newPassword) < 8 || !preg_match('/[0-9]/', $newPassword) || !preg_match('/[\W]/', $newPassword)) {
            throw new Exception("Het nieuwe wachtwoord moet minimaal 8 tekens lang zijn en moet een cijfer en een speciaal teken bevatten.");
        }

        $options = ['cost' => 12];
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT, $options);

        $statement = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
        $statement->bindValue(':password', $newHash);
        $statement->bindValue(':email', $this->email);

        if ($statement->execute()) {
            return "Wachtwoord succesvol bijgewerkt.";
        } else {
            throw new Exception("Er is een fout opgetreden bij het bijwerken van het wachtwoord.");
        }
    }//cimment
    

   
}

?>