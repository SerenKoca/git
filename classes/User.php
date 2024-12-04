<?php
namespace Kocas\Git;

include_once(__DIR__ . '/Db.php');
include_once(__DIR__ . '/Order.php');

use Kocas\Git\Db;
use Kocas\Git\Order;

class User {
    private $id;
    private $email;
    private $password;
    private $balance;

    public function __construct($email = null, $password = null) {
        if ($email) {
            $this->email = $email;
        }
        if ($password) {
            $this->password = $password;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
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
        $statement = $conn->prepare("SELECT id, password FROM users WHERE email = :email");
        $statement->bindValue(':email', $p_email);
        $statement->execute();
        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($p_password, $user['password'])) {
            return $user['id']; // Geef de user_id terug als de login succesvol is
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
    }

    public function getBalance() {
        return $this->balance;
    }

    public function setBalance($balance) {
        if ($balance < 0) {
            throw new Exception("Saldo kan niet negatief zijn.");
        }
        $this->balance = $balance;
        return $this;
    }

    public function initializeBalance() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("UPDATE users SET balance = 1000 WHERE email = :email");
        $statement->bindValue(':email', $this->email);
        return $statement->execute();
    }

    public function deductBalance($amount) {
        // Haal de gegevens van de gebruiker opnieuw op om het laatste saldo te verkrijgen
        $user = User::getUserById($this->id); // Haal de gebruiker op via ID
        $currentBalance = $user['balance'];  // Het huidige saldo van de gebruiker
    
        // Debugging: Weergeven van het huidige saldo en af te trekken bedrag
        echo "Huidig saldo: " . number_format($currentBalance, 2) . "<br>";  
        echo "Af te trekken bedrag: " . number_format($amount, 2) . "<br>";  
    
        if ($currentBalance < $amount) {
            throw new \Exception("Onvoldoende saldo.");
        }
    
        // Verminder het saldo
        $newBalance = $currentBalance - $amount;
    
        // Debugging: Weergeven van het nieuwe saldo
        echo "Nieuw saldo na aftrekken: " . number_format($newBalance, 2) . "<br>";
    
        // Bijwerken van het saldo in de database
        $conn = Db::getConnection();
        $statement = $conn->prepare("UPDATE users SET balance = :balance WHERE id = :id");
        $statement->bindValue(':balance', $newBalance);
        $statement->bindValue(':id', $this->id);
    
        // Voer de query uit en controleer of het is gelukt
        $executed = $statement->execute();
        if ($executed) {
            echo "Saldo succesvol bijgewerkt.<br>";
        } else {
            echo "Fout bij het bijwerken van saldo in de database.<br>";
        }
    }
    
    

    public static function getUserByEmail($email) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(':email', $email);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getUserById($id) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindValue(':id', $id);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }
}
?>
