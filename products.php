<?php 

use Web\XD\Product;
use Web\XD\Db;
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");

session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Haal de geselecteerde categorie op
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

// Haal de zoekterm op
$searchTerm = isset($_GET['search']) ? $_GET['search'] : null;

// Haal de producten op (filter categorie en zoekopdracht)
if ($searchTerm) {
    // Zoek op naam
    $products = Product::searchByName($searchTerm);
} else {
    // categorie
    if ($selectedCategory) {
        $products = Product::getByCategory($selectedCategory);
    } else {
        $products = Product::getAll();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producten</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
    <?php include_once("nav.php"); ?>   
</head>
<body>
    <!-- Categorie navigatie -->
    <nav class="category-nav">
        <a href="products.php">Alle Categorieën</a>
        <a href="products.php?category=hond" <?php echo $selectedCategory == 'hond' ? 'class="active"' : ''; ?>><i class="fas fa-dog"></i> Hond</a>
        <a href="products.php?category=kat" <?php echo $selectedCategory == 'kat' ? 'class="active"' : ''; ?>><i class="fa-solid fa-cat"></i> Kat</a>
        <a href="products.php?category=knaagdier" <?php echo $selectedCategory == 'knaagdier' ? 'class="active"' : ''; ?>><i class="fa-solid fa-otter"></i> Knaagdier</a>
        <a href="products.php?category=vogel" <?php echo $selectedCategory == 'vogel' ? 'class="active"' : ''; ?>><i class="fa-solid fa-crow"></i> Vogel</a>
    </nav>

    <h1>Producten</h1>
    <?php if (empty($products)): ?>
        <p class="no_products">Geen producten gevonden in deze categorie. <i class="fa-solid fa-face-frown"></i></p>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article>
                    <a href="product_detail.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                        <h2><?php echo htmlspecialchars($product['title']); ?></h2>
                        <p>Categorie: <?php echo htmlspecialchars($product['categorie']); ?></p>
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" style="max-width: 200px; height: auto;">
                        <?php else: ?>
                            <p>No image available</p>
                        <?php endif; ?>
                        <h2>€<?php echo number_format($product['price'], 2); ?></h2>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <footer>
        <?php include_once("footer.php"); ?> 
    </footer>
</body>
</html>