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
                $host = "webshopschool.mysql.database.azure.com";
                $db = "webshopschool";
                $user = "Seren";
                $pass = "MLB11il!";
                self::$conn = new \PDO("mysql:host=$host;dbname=$db", $user, $pass);  
                self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
            } catch (\PDOException $e) {
                throw new \Exception("Connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>
