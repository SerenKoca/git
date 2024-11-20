<?php
use Web\XD\User;
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
</head>
<body>
    
<header>
<?php include_once("nav_admin.php"); ?>
    </header>

    <div class="admin-container">
        <h1>Welcome to the Admin Panel</h1>
        <p class="intro-text">Here are the actions you can take:</p>

        <div class="admin-options">
            <div class="admin-option">
                <a href="addProduct.php" class="admin-link">
                    <div class="link-content">
                        <h2>Add a New Product</h2>
                        <p>Click here to add a new product to the shop.</p>
                    </div>
                </a>
            </div>

            <div class="admin-option">
                <a href="products_admin.php" class="admin-link">
                    <div class="link-content">
                        <h2>View All Products</h2>
                        <p>Manage and edit existing products.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

</body>
</html>
