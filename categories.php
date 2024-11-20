<?php 
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Category.php");

use Web\XD\Category;

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

$categories = Category::getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorieën Beheren</title>
    <link rel="stylesheet" href="webshop.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header>
<?php include_once("nav_admin.php"); ?>
    </header>

<h1>Categorieën Beheren</h1>

<!-- Toevoegen van een categorie knop -->
<div>
    <a href="category_add.php" class="btn-add-category">
        <i class="fas fa-plus"></i> Categorie Toevoegen
    </a>
</div>

<h2>Bestaande Categorieën</h2>
<ul>
    <?php foreach ($categories as $category): ?>
        <li>
            <!-- Toon het icoon naast de categorie naam -->
            <i class="<?php echo htmlspecialchars($category['icon']); ?>"></i> 
            <?php echo htmlspecialchars($category['name']); ?>
        </li>
    <?php endforeach; ?>
</ul>

<?php include_once("footer.php"); ?>

</body>
</html>
