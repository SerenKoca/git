<?php 

use Web\XD\Product;
use Web\XD\Db; 
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $productId = (int)$_GET['id'];
    $conn = Db::getConnection();

    $statement = $conn->prepare('SELECT * FROM products WHERE id = :id');
    $statement->bindValue(':id', $productId, PDO::PARAM_INT);
    $statement->execute();
    $product = $statement->fetch(PDO::FETCH_ASSOC);

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
            <p class="product-category"><strong>Categorie:</strong> <?php echo htmlspecialchars($product['categorie']); ?></p>

            <div class="product-description">
                <h2>Beschrijving</h2>
                <p><?php echo htmlspecialchars($product['description'] ?? "Geen beschrijving beschikbaar."); ?></p>
            </div>

            <a href="products.php" class="back-button">Terug naar producten</a>
        </div>
    </div>

    <footer>
        <?php include_once("footer.php"); ?> 
    </footer>
</body>
</html>
