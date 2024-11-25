<?php
use Web\XD\Product;
use Web\XD\Db;

include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");
include_once(__DIR__ . '/classes/Comment.php');

session_start();
    $allComments = Comment::getAll($productId);


// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Haal het product ID uit de URL
if (isset($_GET['id'])) {
    $productId = (int)$_GET['id'];

    // Haal het product op via de Product klasse
    $product = Product::getById($productId);

    if (!$product) {
        echo "Product niet gevonden.";
        exit;
    }
} else {
    echo "Geen product ID opgegeven.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?></title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
</head>
<body>
<?php include_once("nav.php"); ?> 

<div class="product-detail-container">
    <div class="product-image">
        <?php if (!empty($product['image'])): ?>
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
        <?php else: ?>
            <div class="no-image">Afbeelding niet beschikbaar</div>
        <?php endif; ?>
    </div>

    <div class="product-info">
        <h1><?php echo htmlspecialchars($product['title']); ?></h1>
        <p class="product-price">€<?php echo number_format($product['price'], 2); ?></p>
        <p class="product-category"><strong>Categorie:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>

        <div class="product-description">
            <h2>Beschrijving</h2>
            <p><?php echo htmlspecialchars($product['description'] ?? "Geen beschrijving beschikbaar."); ?></p>
        </div>

        <div class="post__comments">
    <div class="post__comments__form">
        <input type="text" id="commentText" placeholder="What do you think about this product?">
        <a href="#" class="btn" id="btnAddComment" data-postid="<?php echo $productId; ?>">Add Comment</a>
    </div>

    <ul class="post__comments__list">
        <?php foreach($allComments as $comment): ?>
            <li><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['text']); ?></li>  
        <?php endforeach; ?>
    </ul>
</div>

        <a href="products.php" class="back-button">Terug naar producten</a>
    </div>
</div>

<footer>
    <?php include_once("footer.php"); ?> 
</footer>

<script src="app.js"></script>
</body>
</html>