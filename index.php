<?php 

    //PDO connection
    $conn = new PDO('mysql:dbname=webshop;host=localhost', "root", "");

    //select * from products and fetch as array
    $statement = $conn->prepare('SELECT * FROM products');
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);



    session_start(); //connectie maken met cookie code om sessie te lezen
    if(isset($_SESSION['loggedin'])){
        //user is logged in 
        echo "Welcome ". $_SESSION['email'];
    }else{
        //user is not logged in
        header("Location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>The private Dashboard</h1>
    <?php foreach($products as $product): ?>
    <article>
        <h2><?php echo $product['title']; ?> : <?php echo $product['price']; ?></h2>
        <a href="logout.php">Log out</a>
    </article>
    <?php endforeach; ?>
</body>
</html>