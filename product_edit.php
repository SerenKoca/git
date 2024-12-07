<?php
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");
require_once __DIR__ . '/bootstrap.php';
use Kocas\Git\Product;
use Kocas\Git\Db;
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Haal de product ID op
if (!isset($_GET['id'])) {
    header("Location: products_admin.php"); // Redirect if no product ID is provided
    exit;
}

$productId = $_GET['id'];
$product = Product::getById($productId);

if ($product === null) {
    // If no product is found, show an error or redirect
    echo "Product not found!";
    exit;
}

// Haal de categorieën op uit de database
$categories = Product::getCategories(); // Haal alle categorieën uit de database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verwerk formulier en update het product
    $title = $_POST['title'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $_FILES['image']; // Verkrijg de geüploade afbeelding
    $description = $_POST['description']; // Haal de beschrijving op

    // Als er een nieuwe afbeelding is geüpload, upload deze dan naar Cloudinary
    if ($image['error'] === 0) {
        try {
            // Hier wordt de afbeelding geüpload naar Cloudinary
            $product->uploadImage($image); // Gebruik de uploadImage methode om de afbeelding naar Cloudinary te sturen
        } catch (\Exception $e) {
            // Afbeelding uploaden mislukt, foutmelding
            $error = $e->getMessage();
        }
    }


    // Werk het product bij in de database
    Product::update($productId, $title, $category, $price, $product->getImage(), $description);

    // Redirect naar de productlijst na het bijwerken
    header("Location: products_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Edit</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
    <link rel="icon" type="image/x-icon" href="uploads/paw.avif">
</head>
<body>

<header>
<?php include_once("nav_admin.php"); ?>
</header>

<h1>Wijzig Product</h1>

<form action="product_edit.php?id=<?php echo $product['id']; ?>" method="POST">
    <label for="title">Product Titel:</label>
    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>

    <label for="category">Categorie:</label>
    <select name="category" id="category" required>
        <option value="">Selecteer een categorie</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($category['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="price">Prijs (€):</label>
    <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" required step="0.01">

    <label for="image">Afbeelding (Kies een bestand):</label>
    <input type="file" name="image" id="image">

    <label for="description">Beschrijving:</label>
    <textarea name="description" id="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

    <button type="submit">Wijzig Product</button>
</form>

</body>
</html>
