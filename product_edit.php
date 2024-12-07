<?php
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");
require_once __DIR__ . '/bootstrap.php';
use Kocas\Git\Product;
use Kocas\Git\Db;
session_start();

$error = "";
$success = "";

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
    // Haal waarden op uit het formulier
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = trim($_POST['description']);

    // Validatie van velden
    if (empty($title) || empty($category) || empty($price) || empty($description)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = "Please enter a valid price.";
    } else {
        // We are not updating the image, so we keep the old image URL
        $newImageUrl = $product['image']; // Keep the current image

        // Werk het product bij
        Product::update($productId, $title, $category, $price, $newImageUrl, $description);

        // Redirect or success message
        $success = "Product successfully updated!";
        header("Location: products_admin.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="icon" type="image/x-icon" href="uploads/paw.avif">
</head>
<body>
    <?php include_once("nav_admin.php"); ?>

    <div class="container add-product-page">
        <h1>Edit Product</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="product_edit.php?id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Product Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="price">Price (€)</label>
                <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>" 
                            <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <!-- Display the current image, but do not allow image modification -->
            <div class="form-group">
                <label for="image">Product Image</label>
                <?php if (!empty($product['image'])): ?>
                    <div class="current-image">
                        <p>Current Image:</p>
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
                <label for="image">Product Image</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <input type="submit" value="Update Product" class="btn">
        </form>
    </div>
</body>
</html>
