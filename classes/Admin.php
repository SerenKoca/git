<?php

namespace Kocas\Git;

use Kocas\Git\Db;

class Admin extends User {

    // De isAdmin methode kan hier gewoonweg de logica van de admin verificatie bevatten
    public function isAdmin() {
        // Controleer via de email of de gebruiker admin is
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT is_admin FROM users WHERE email = :email");
        $statement->bindValue(':email', $this->getEmail());
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return isset($result['is_admin']) && $result['is_admin'] == 1;
    }
}
?>
