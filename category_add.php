<?php 
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Category.php");
require_once __DIR__ . '/bootstrap.php';

use Kocas\Git\Category;

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['name'] ?? '';
    $categoryIcon = $_POST['icon'] ?? '';
    
    try {
        if (empty($categoryName)) {
            throw new Exception("Categorie naam mag niet leeg zijn.");
        }
        if (empty($categoryIcon)) {
            throw new Exception("Icoon mag niet leeg zijn.");
        }
        Category::addCategory($categoryName, $categoryIcon);
        $successMessage = "Categorie succesvol toegevoegd.";
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorie Toevoegen</title>
    <link rel="stylesheet" href="webshop.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="uploads/paw.avif">
</head>
<body>

<header>
<?php include_once("nav_admin.php"); ?>
    </header>

<h1>Categorie Toevoegen</h1>

<?php if (isset($successMessage)): ?>
    <p class="success"><?php echo htmlspecialchars($successMessage); ?></p>
<?php endif; ?>

<?php if (isset($errorMessage)): ?>
    <p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
<?php endif; ?>

<form method="POST">
    <label for="name">Nieuwe Categorie</label>
    <input type="text" id="name" name="name" required placeholder="Bijv. Hond, Kat, Vogel">

    <label for="icon">Icon (https://fontawesome.com/icons)</label>
    <input type="text" id="icon" name="icon" placeholder="Bijv. fa-dog">

    <button type="submit">Categorie Toevoegen</button>
</form>

<?php include_once("footer.php"); ?>

</body>
</html>
