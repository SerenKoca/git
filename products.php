<?php 
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");

session_start(); // Start de sessie

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Haal de geselecteerde categorie op (indien aanwezig)
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

// Haal producten op (filter op categorie indien geselecteerd)
$products = $selectedCategory ? Product::getByCategory($selectedCategory) : Product::getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producten</title>
    <link rel="stylesheet" href="webshop.css">
    <?php include_once("nav.php"); ?>   
</head>
<body>
     <!-- Categorie navigatie -->
    <nav class="category-nav">
            <a href="products.php">Alle Categorieën</a>
            <a href="products.php?category=hond" <?php echo $selectedCategory == 'hond' ? 'class="active"' : ''; ?>>Hond</a>
            <a href="products.php?category=kat" <?php echo $selectedCategory == 'kat' ? 'class="active"' : ''; ?>>Kat</a>
            <a href="products.php?category=knaagdier" <?php echo $selectedCategory == 'knaagdier' ? 'class="active"' : ''; ?>>Knaagdier</a>
            <a href="products.php?category=vogel" <?php echo $selectedCategory == 'vogel' ? 'class="active"' : ''; ?>>Vogel</a>
    </nav>
    
    <h1>Producten</h1>
    
    <?php if (empty($products)): ?>
        <p>Geen producten gevonden in deze categorie.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <article>
                <h2>
                    <a href="product_detail.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                        <?php echo htmlspecialchars($product['title']); ?> : €<?php echo number_format($product['price'], 2); ?>
                    </a>
                </h2>
                <p>Categorie: <?php echo htmlspecialchars($product['categorie']); ?></p>
                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" style="max-width: 200px; height: auto;">
                <?php else: ?>
                    <p>No image available</p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

    <footer>
        <?php include_once("footer.php"); ?> 
    </footer>
</body>
</html>