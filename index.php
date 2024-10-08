<?php 

    session_start(); //connectie maken met cookie code om sessie te lezen
    if(isset($_SESSION['username'])){
        //user is logged in 
        echo "Welcome ". $_SESSION['username'];
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
</body>
</html>