<?php
use Kocas\Git\Product;
use Kocas\Git\Db;

include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");
require_once __DIR__ . '/bootstrap.php';

session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Haal het product ID uit de URL
if (isset($_GET['id'])) {
    $productId = (int)$_GET['id'];

    // Haal het product op via de Product klasse
    $product = Product::getById($productId);

    if (!$product) {
        echo "Product niet gevonden.";
        exit;
    }
} else {
    echo "Geen product ID opgegeven.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?></title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
</head>
<body>
    <?php include_once("nav.php"); ?> 

    <div class="product-detail-container">
        <div class="product-image">
            <?php if (!empty($product['image'])): ?>
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
            <?php else: ?>
                <div class="no-image">Afbeelding niet beschikbaar</div>
            <?php endif; ?>
        </div>

        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['title']); ?></h1>
            <p class="product-price">€<?php echo number_format($product['price'], 2); ?></p>
            <p class="product-category"><strong>Categorie:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>

            <div class="product-description">
                <h2>Beschrijving</h2>
                <p><?php echo htmlspecialchars($product['description'] ?? "Geen beschrijving beschikbaar."); ?></p>
            </div>

            <a href="products_admin.php" class="back-button">Terug naar producten</a>
        </div>
    </div>

    <footer>
        <?php include_once("footer.php"); ?> 
    </footer>
</body>
</html>
