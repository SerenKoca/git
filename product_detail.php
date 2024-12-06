<?php
use Kocas\Git\Product;
use Kocas\Git\Db;
use Kocas\Git\Comment;
use Kocas\Git\Order;

include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");
include_once(__DIR__ . "/classes/Comment.php");
include_once(__DIR__ . "/classes/Order.php");
require_once __DIR__ . '/bootstrap.php';

session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Haal de gebruikers ID uit de sessie (zorg ervoor dat dit beschikbaar is)
$userId = $_SESSION['user_id']; // Stel dat de gebruiker ID is opgeslagen in de sessie

// Haal het product ID uit de URL
if (isset($_GET['id'])) {
    $productId = (int)$_GET['id'];
    error_log("Product ID ontvangen: $productId");

    // Controleer of de gebruiker het product heeft gekocht
    $userCanComment = false; // Standaard: gebruiker mag geen comment plaatsen
    if (Order::hasUserPurchasedProduct($userId, $productId)) {
        $userCanComment = true; // Gebruiker heeft het product gekocht, dus mag commenten
    }

    // Haal het product op via de Product klasse
    $product = Product::getById($productId);
    if (!$product) {
        error_log("Product niet gevonden voor ID: $productId");
        echo "Product niet gevonden.";
        exit;
    }

    // Haal alle reacties op
    $allComments = Comment::getAll($productId);
    error_log("Aantal reacties gevonden voor product $productId: " . count($allComments));
} else {
    error_log("Geen product ID opgegeven in URL.");
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

            <form method="post" action="winkelmandje.php">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

    <!-- Maat selecteren (optioneel) -->
    <?php if ($product['requires_size'] == '1'): ?>
        <label for="size">Kies een maat (optioneel):</label>
        <select name="size" id="size">
            <option value="">Geen maat</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <!-- Voeg hier meer maten toe indien nodig -->
        </select>
    <?php else: ?>
        <input type="hidden" name="size" value="Geen maat">
    <?php endif; ?>

    <!-- Hoeveelheid -->
    <input type="number" name="quantity" value="1" min="1" required>
    <button type="submit" name="add_to_cart">Toevoegen aan winkelwagen</button>
</form>

            <br>
                
            <!-- Debugging comments -->
            <div class="post__comments">
                <?php if ($userCanComment): ?>
                    <div class="post__comments__form">
                        <input type="text" id="commentText" placeholder="What's on your mind">
                        <a href="#" class="btn" id="btnAddComment" data-postid="<?php echo $productId; ?>">Voeg commentaar toe</a>
                    </div>  

                    <ul class="post__comments__list">
                        <?php foreach ($allComments as $comment): ?>
                            <li><?php echo htmlspecialchars($comment['text'], ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Je moet dit product eerst kopen voordat je een reactie kunt plaatsen.</p>
                <?php endif; ?>
            </div>

            <a href="products_admin.php" class="back-button">Terug naar producten</a>
        </div>
    </div>

    <script src="app.js"></script>

    <footer>
        <?php include_once("footer.php"); ?> 
    </footer>
</body>
</html>
