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

// Controleer of het product bestaat
if ($product === null) {
    echo "Product niet gevonden!";
    exit;
}

// Verkrijg de afbeelding uit de array
$imageUrl = isset($product->image) ? $product->image : ''; // Controleer of de afbeelding bestaat

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
            $imageUrl = $product->getImage(); // Verkrijg de URL van de geüploade afbeelding
        } catch (\Exception $e) {
            // Afbeelding uploaden mislukt, foutmelding
            $error = $e->getMessage();
        }
    }

    // Werk het product bij in de database
    Product::update($productId, $title, $category, $price, $imageUrl, $description);

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
    <title>Add Product</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="icon" type="image/x-icon" href="uploads/paw.avif">
</head>
<body>
    <?php include_once("nav_admin.php"); ?>

    <div class="container add-product-page">
        <h1>Add New Product</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Product Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="price">Price (€)</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <input type="submit" value="Add Product" class="btn">
        </form>
    </div>
</body>
</html>
