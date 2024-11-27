<?php 

include_once(__DIR__ . "/classes/Product.php");

use Kocas\Git\Product;

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
    // Categorie filter
    if ($selectedCategory) {
        $products = Product::getByCategory($selectedCategory);
    } else {
        $products = Product::getAll();
    }
}

// Haal categorieën op voor navigatie
$categories = Product::getCategories();
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
</head>
<body>
    <?php include_once("nav.php"); ?> 

    <!-- Categorie navigatie -->
    <nav class="category-nav">
        <a href="products.php" class="<?= !$selectedCategory ? 'active' : ''; ?>">Alle Categorieën</a>
        <?php foreach ($categories as $category): ?>
            <a href="products.php?category=<?= urlencode($category['name']); ?>" class="<?= $selectedCategory === $category['name'] ? 'active' : ''; ?>">
                <?php if (!empty($category['icon'])): ?>
                    <i class="<?= htmlspecialchars($category['icon']); ?>"></i>
                <?php endif; ?>
                <?= htmlspecialchars(ucfirst($category['name'])); ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <h1>Producten</h1>
    <?php if (empty($products)): ?>
        <p class="no_products">Geen producten gevonden in deze categorie. <i class="fa-solid fa-face-frown"></i></p>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article>
                    <a href="product_detail.php?id=<?= htmlspecialchars($product['id']); ?>">
                        <h2><?= htmlspecialchars($product['title']); ?></h2>
                        <p>Categorie: <?= htmlspecialchars($product['category_name']); ?></p> <!-- Gebruik category_name -->
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['title']); ?>" style="max-width: 200px; height: auto;">
                        <?php else: ?>
                            <p>No image available</p>
                        <?php endif; ?>
                        <h2>€<?= number_format($product['price'], 2); ?></h2>
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
