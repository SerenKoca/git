<?php
    class Db{
        private static $conn = null;

        public static function getConnection(){
            if(self::$conn == null){
                echo '🔥';
                self::$conn = new PDO('mysql:host=localhost;dbname=webshop', 'root', '');
                return self::$conn;
            }else{
                echo '🦄';
                return self::$conn;
            }
        }
    }

?>