<?php 
include_once(__DIR__ . '/classes/Db.php');
include_once(__DIR__ . '/classes/Product.php');
use Kocas\Git\Product;

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Haal categorieën dynamisch op uit de database
$categories = Product::getCategories(); // Haal alle categorieën op uit de database

// Haal de geselecteerde categorie op uit de URL
$selectedCategory = $_GET['category'] ?? null;
$searchTerm = $_GET['search'] ?? null;

// Haal producten op (met filters indien aanwezig)
if ($selectedCategory) {
    // Filter op basis van de geselecteerde categorie
    $products = Product::getByCategory($selectedCategory);
} elseif ($searchTerm) {
    // Filter op basis van de zoekterm
    $products = Product::searchByName($searchTerm);
} else {
    // Haal alle producten op als er geen filter is
    $products = Product::getAll();
}

// Verwijder product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product_id'])) {
    Product::deleteById($_POST['delete_product_id']);
    header("Location: products_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Producten</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css"> 
</head>
<body>
<header>
    <?php include_once("nav_admin.php"); ?>
</header>

<main>
    <!-- Dynamische categorie navigatie -->
    <nav class="category-nav">
        <a href="products_admin.php" class="<?= !$selectedCategory ? 'active' : ''; ?>">Alle Categorieën</a>
        <?php foreach ($categories as $category): ?>
            <a href="products_admin.php?category=<?= urlencode($category['name']); ?>" 
               class="<?= $selectedCategory === $category['name'] ? 'active' : ''; ?>">
               <?php if (!empty($category['icon'])): ?>
                   <i class="<?= htmlspecialchars($category['icon']); ?>"></i>
               <?php endif; ?>
               <?= htmlspecialchars(ucfirst($category['name'])); ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <h1>Producten</h1>

    <?php if (empty($products)): ?>
        <p class="no-products">Geen producten gevonden voor "<?= htmlspecialchars($searchTerm) ?>" in deze categorie. <i class="fa-solid fa-face-frown"></i></p>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article>
                    <a href="product_detail_admin.php?id=<?= htmlspecialchars($product['id']); ?>">
                        <h2><?= htmlspecialchars($product['title']); ?></h2>
                        <p>Categorie: <?= htmlspecialchars($product['category_name']); ?></p> <!-- Verander hier naar category_name -->
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['title']); ?>" class="product-image">
                        <?php else: ?>
                            <p class="no-image">Geen afbeelding beschikbaar</p>
                        <?php endif; ?>
                        <h2>€<?= number_format($product['price'], 2); ?></h2>
                    </a>
                    <div class="product-actions">
                        <form action="products_admin.php" method="POST" onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">
                            <input type="hidden" name="delete_product_id" value="<?= $product['id']; ?>">
                            <button type="submit" class="delete-btn">
                                <i class="fa-solid fa-trash-can"></i> Verwijder
                            </button>
                        </form>
                        <form action="product_edit.php" method="GET">
                            <input type="hidden" name="id" value="<?= $product['id']; ?>">
                            <button type="submit" class="edit-btn">
                                <i class="fa-solid fa-pen"></i> Wijzig
                            </button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    <?php include_once("footer.php"); ?>
</footer>
</body>
</html>
