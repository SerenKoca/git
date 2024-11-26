<?php

namespace Kocas\Git;

use Exception;

class Customer extends User {

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

    // Andere methodes zoals getEmail(), setEmail(), getPassword(), etc.
}
?>
