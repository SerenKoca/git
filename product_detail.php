<?php 
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Product.php");
session_start(); // Start de sessie

if (!isset($_SESSION['loggedin'])) {
    // Gebruiker is niet ingelogd
    header("Location: login.php");
    exit;
}

// Controleer of een ID is meegegeven in de URL
if (isset($_GET['id'])) {
    $productId = (int)$_GET['id']; // Zet het ID om naar een integer
    $conn = Db::getConnection();

    // Zoek het product op basis van het ID
    $statement = $conn->prepare('SELECT * FROM products WHERE id = :id');
    $statement->bindValue(':id', $productId, PDO::PARAM_INT);
    $statement->execute();
    $product = $statement->fetch(PDO::FETCH_ASSOC);

    // Controleer of het product bestaat
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
    <?php include_once("nav.php"); ?>   
</head>
<body>
    <h1><?php echo htmlspecialchars($product['title']); ?></h1>
    <p>Prijs: €<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
    <p>Beschrijving: <?php echo htmlspecialchars($product['description']); ?></p> <!-- Als je een beschrijving hebt -->
    
    <a href="products.php">Terug naar producten</a>

    <footer>
        <?php include_once("footer.php"); ?> 
    </footer>
</body>
</html>