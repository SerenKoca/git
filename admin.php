<?php
use Kocas\Git\User;

require_once __DIR__ . '/bootstrap.php';
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
    <title>Admin Paneel</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
    <link rel="icon" type="image/x-icon" href="uploads/paw.avif">
</head>
<body>
    
<header>
<?php include_once("nav_admin.php"); ?>
    </header>

    <div class="admin-container">
        <h1>Welkom op het admin paneel!</h1>
        <p class="intro-text">Hier zijn er verschillende opties om te doen:</p>

        <div class="admin-options">
            <div class="admin-option">
                <a href="addProduct.php" class="admin-link">
                    <div class="link-content">
                        <h2>Product toeveogen</h2>
                        <p>Klik hier om meer producten aan de webshop toe te voegen.</p>
                    </div>
                </a>
            </div>

            <div class="admin-option">
                <a href="products_admin.php" class="admin-link">
                    <div class="link-content">
                        <h2>Alle producten</h2>
                        <p>bekijk en manage alle producten hier</p>
                    </div>
                </a>
            </div>

            <div class="admin-option">
                <a href="categories.php" class="admin-link">
                    <div class="link-content">
                        <h2>Alle categorieën</h2>
                        <p>bekijk en manage alle categorieën hier</p>
                    </div>
                </a>
            </div>
        </div>


    </div>

</body>
</html>
