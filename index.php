<?php 
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");
    //PDO connection
    $conn = new PDO('mysql:dbname=webshop;host=localhost', "root", "");

    //select * from products and fetch as array
    $statement = $conn->prepare('SELECT * FROM products');
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);



    session_start(); //connectie maken met cookie code om sessie te lezen
    if(isset($_SESSION['loggedin'])){
        //user is logged in 
        
    }else{
        //user is not logged in
        header("Location: login.php");
    }

    $products = Product::getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="webshop.css">
    <?php include_once("nav.php"); ?>   
</head>
<body>
    <h1>The private Dashboard</h1>

    <footer>
        <?php include_once("footer.php"); ?> 
    </footer>
</body>
</html>