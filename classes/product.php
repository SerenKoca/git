<?php
    include_once(__DIR__ . "/Db.php");

    class Product{
        private $title;
        private $price;


        public static function getAll(){
            $conn = Db::getConnection();
            $statement = $conn->query("SELECT * FROM products");
            return $statement->fetchAll();
        }
    }


?>