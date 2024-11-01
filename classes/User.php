<?php

include_once(__DIR__ . "/Db.php");

class User {
    private $email;
    private $password;

    /**
     * Get the value of email
     */ 
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email) {
        if (empty($email)) {
            throw new Exception("Email cannot be empty");
        }
        $this->email = $email;
        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password) {
        if (empty($password)) {
            throw new Exception("Password cannot be empty");
        }
        $this->password = $password;
        return $this;
    }

    /**
     * Save the user data into the database
     */
    


     public function save(){
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Hash the password with a security cost factor
            $options = ['cost' => 12];
            $hash = password_hash($password, PASSWORD_DEFAULT, $options);

            // Get database connection
            $conn = Db::getConnection();
            $statement = $conn->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
            $statement->bindValue(':email', $email); // Safe against SQL injection
            $statement->bindValue(':password', $hash); // Safe against SQL injection
            $statement->execute();

            // Return success
            return true;
        } else {
            // Throw exception if either email or password is missing
            throw new Exception("Email and password are required.");
        }
        
    }

    public function canLogin($p_email, $p_password) {
        try {
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
        } catch (PDOException $e) {
            throw new Exception('Database error: ' . $e->getMessage());
        }
    
    }

    public function isAdmin() {
        return $this->email === 'admin'; // Vervang door het juiste admin e-mailadres
    }

    }

    

?>