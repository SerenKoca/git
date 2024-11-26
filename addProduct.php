<?php

include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Category.php");
include_once(__DIR__ . "/classes/Product.php");

use Kocas\Git\Category;
use Kocas\Git\Product;
use Kocas\Git\Db;

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

// Haal de categorieën op uit de database
$categories = Category::getAllCategories();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $product = new Product();
        $product->setTitle($_POST['title'])
                ->setPrice($_POST['price'])
                ->setCategoryId($_POST['category'])
                ->setDescription($_POST['description']);

        // Afbeelding uploaden
        $product->uploadImage($_FILES['image']);

        // Product toevoegen
        if ($product->addProduct()) {
            $success = "Product successfully added!";
        } else {
            $error = "Failed to add product.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="webshop.css">
</head>
<body>
    <?php include_once("nav_admin.php"); ?>

    <div class="container add-product-page">
        <h1>Add New Product</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
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
                        <option value="<?php echo $category['id']; ?>">
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