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

    public function changePassword($currentPassword, $newPassword) {
        // Stap 1: Verifieer dat de gebruiker het juiste huidige wachtwoord invoert
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT password FROM users WHERE email = :email");
        $statement->bindValue(':email', $this->email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
    
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            throw new Exception("Huidige wachtwoord is onjuist.");
        }
    
        // Stap 2: Valideer het nieuwe wachtwoord (bijv. minimaal 8 tekens, bevat een cijfer en een speciaal teken)
        if (strlen($newPassword) < 8 || !preg_match('/[0-9]/', $newPassword) || !preg_match('/[\W]/', $newPassword)) {
            throw new Exception("Het nieuwe wachtwoord moet minimaal 8 tekens lang zijn en moet een cijfer en een speciaal teken bevatten.");
        }
    
        // Stap 3: Versleutel het nieuwe wachtwoord
        $options = ['cost' => 12];
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT, $options);
    
        // Stap 4: Update het wachtwoord in de database
        $statement = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
        $statement->bindValue(':password', $newHash);
        $statement->bindValue(':email', $this->email);
    
        if ($statement->execute()) {
            return "Wachtwoord succesvol bijgewerkt.";
        } else {
            throw new Exception("Er is een fout opgetreden bij het bijwerken van het wachtwoord.");
        }
    }
}
?>