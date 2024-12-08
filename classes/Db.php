<?php
namespace Kocas\Git;

class Db {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn == null) {
            try {
                // Haal de omgevingsvariabelen op
                $host = getenv('DB_HOST'); // Haalt de DB_HOST op
                $db = getenv('DB_NAME'); // Haalt de DB_NAME op
                $user = getenv('DB_USER'); // Haalt de DB_USER op
                $pass = getenv('DB_PASS'); // Haalt het DB_PASS op
                $port = getenv('DB_PORT'); // Haalt de DB_PORT op

                // Maak de PDO-verbinding
                self::$conn = new \PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
                self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
                self::$conn->exec("SET time_zone = 'Europe/Amsterdam'");
            } catch (\PDOException $e) {
                throw new \Exception("Connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}

?>
