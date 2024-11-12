<?php 
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");

session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Haal de producten op om te tonen op de homepage (korte selectie)
$products = Product::getAll(); // Je kan hier ook een andere manier gebruiken om producten te selecteren (bijvoorbeeld featured producten)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom - Webshop</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
    <?php include_once("nav.php"); ?>
</head>
<body>
    <section class="intro">
        <div class="container">
            <h1>Welkom bij de Webshop!</h1>
            <p>Ontdek de mooiste producten voor je huisdier en bestel eenvoudig online.</p>
        </div>
    </section>

    <section class="featured-products">
        <div class="container">
            <h2>Onze aanbevolen producten</h2>
            <div class="product-grid">
                <?php if (empty($products)): ?>
                    <p>Er zijn momenteel geen producten beschikbaar.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <article>
                            <a href="product_detail.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="product-link">
                                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                                <p>Categorie: <?php echo htmlspecialchars($product['categorie']); ?></p>
                                <?php if (!empty($product['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="product-image">
                                <?php else: ?>
                                    <p>Geen afbeelding beschikbaar</p>
                                <?php endif; ?>
                                <h4>€<?php echo number_format($product['price'], 2); ?></h4>
                            </a>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a href="products.php" class="btn secondary-btn">Bekijk alle producten</a>
        </div>
    </section>

    <footer>
        <?php include_once("footer.php"); ?> 
    </footer>
</body>
</html>
