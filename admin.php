<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Admin pagina content
echo "<h1>Welcome to the Admin Panel</h1>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="webshop.css">
</head>
<body>

    <header>
        <nav class="nav">
            <a href="admin.php">Admin Panel</a> |
            <a href="addProduct.php">Product toevoegen</a> |
            <a href="logout.php">Log Out</a>
        </nav>
    </header>

    <div class="container">
        <h1>Add New Product</h1>
    </div>
</body>
</html>