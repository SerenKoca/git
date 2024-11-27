<?php
namespace Kocas\Git;

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
                /*$host = "webshopschool.mysql.database.azure.com";
                $db = "webshop";
                $user = "Seren";
                $pass = "MLB11il!";*/
                $host = "junction.proxy.rlwy.net"; // De hostnaam van Railway
                $db = "webshop"; // De database naam
                $user = "root"; // De gebruikersnaam van Railway
                $pass = "RkqSzzWiJnwDjxpCsuNydvdSBCWpbGxG"; // Het wachtwoord van Railway
                $port = "26217"; // De poort die Railway gebruikt

                // Maak de PDO-verbinding
                self::$conn = new \PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
                self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
            } catch (\PDOException $e) {
                throw new \Exception("Connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>
