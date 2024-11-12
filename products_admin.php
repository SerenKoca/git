<?php 
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Haal de geselecteerde categorie op (indien aanwezig)
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

// Haal de zoekterm op (indien aanwezig)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : null;

// Haal de producten op (filter op categorie en zoekopdracht indien aanwezig)
if ($searchTerm) {
    // Zoek op naam
    $products = Product::searchByName($searchTerm);
} else {
    // Haal producten op op basis van de geselecteerde categorie, of alle producten als er geen categorie is
    if ($selectedCategory) {
        $products = Product::getByCategory($selectedCategory);
    } else {
        $products = Product::getAll();
    }
}

if (isset($_POST['delete_product_id'])) {
    $productIdToDelete = $_POST['delete_product_id'];
    Product::deleteById($productIdToDelete);
    header("Location: products_admin.php"); // Redirect after deletion
    exit;
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
</head>
<body>
<header>
        <nav class="nav">
            <div>
            <a href="admin.php">Admin Panel</a> |
            <a href="addProduct.php">Product toevoegen</a> |
            <a href="products_admin.php">Producten</a>
            </div>
            <a href="logout.php">Log Out</a>
        </nav>
    </header>
    <!-- Categorie navigatie -->
    <nav class="category-nav">
        <a href="products_admin.php">Alle Categorieën</a>
        <a href="products_admin.php?category=hond" <?php echo $selectedCategory == 'hond' ? 'class="active"' : ''; ?>><i class="fas fa-dog"></i> Hond</a>
        <a href="products_admin.php?category=kat" <?php echo $selectedCategory == 'kat' ? 'class="active"' : ''; ?>><i class="fa-solid fa-cat"></i> Kat</a>
        <a href="products_admin.php?category=knaagdier" <?php echo $selectedCategory == 'knaagdier' ? 'class="active"' : ''; ?>><i class="fa-solid fa-otter"></i> Knaagdier</a>
        <a href="products_admin.php?category=vogel" <?php echo $selectedCategory == 'vogel' ? 'class="active"' : ''; ?>><i class="fa-solid fa-crow"></i> Vogel</a>
    </nav>

    <h1>Producten</h1>
    <?php if (empty($products)): ?>
        <p class="no_products">Geen producten gevonden voor "<?php echo htmlspecialchars($searchTerm); ?>" in deze categorie. <i class="fa-solid fa-face-frown"></i></p>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article>
                    <a href="product_detail_admin.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                        <h2><?php echo htmlspecialchars($product['title']); ?></h2>
                        <p>Categorie: <?php echo htmlspecialchars($product['categorie']); ?></p>
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" style="max-width: 200px; height: auto;">
                        <?php else: ?>
                            <p>No image available</p>
                        <?php endif; ?>
                        <h2>€<?php echo number_format($product['price'], 2); ?></h2>
                    </a>
                    <form action="products_admin.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        <input type="hidden" name="delete_product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="delete-btn">
                            <i class="fa-solid fa-trash-can"></i> Verwijder
                        </button>
                    </form>
                            <br>
                    <form action="product_edit.php" method="GET">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="edit-btn">
                            <i class="fa-solid fa-pen"></i> Wijzig
                        </button>
                    </form>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <footer>
        <?php include_once("footer.php"); ?> 
    </footer>
</body>
</html>