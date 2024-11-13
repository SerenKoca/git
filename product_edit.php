<?php
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");
use Web\XD\Product;
use Web\XD\Db;
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verwerk formulier en update het product
    $title = $_POST['title'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $_POST['image']; // Optionally, handle image upload
    $description = $_POST['description']; // Haal de beschrijving op

    Product::update($productId, $title, $category, $price, $image, $description);

    header("Location: products_admin.php"); // Redirect to products list after update
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
</head>
<body>
    <h1>Wijzig Product</h1>

    <form action="product_edit.php?id=<?php echo $product['id']; ?>" method="POST">
        <label for="title">Product Titel:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>

        <label for="category">Categorie:</label>
        <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($product['categorie']); ?>" required>

        <label for="price">Prijs (€):</label>
        <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" required step="0.01">

        <label for="image">Afbeelding URL:</label>
        <input type="text" name="image" id="image" value="<?php echo htmlspecialchars($product['image']); ?>">

        <label for="description">Beschrijving:</label>
        <textarea name="description" id="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

        <button type="submit">Wijzig Product</button>
    </form>
</body>
</html>