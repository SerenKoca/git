<?php
namespace Web\XD;



$pathToSSL = __DIR__ . '/CA.pem';
$options = array(
    \PDO::MYSQL_ATTR_SSL_CA => $pathToSSL
);




class Db {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn == null) {
            try {
                // Zorg ervoor dat je \PDO gebruikt, zodat het de globale PHP-klasse is

                self::$conn = new \PDO('mysql:host=localhost;dbname=webshop', 'root', ''); 
                self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
            } catch (\PDOException $e) {
                throw new \Exception("Connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>
