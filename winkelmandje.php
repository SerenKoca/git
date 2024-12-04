<?php
use Kocas\Git\Order;
use Kocas\Git\Product;


include_once(__DIR__ . "/classes/Order.php");
include_once(__DIR__ . "/classes/Product.php");

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Maak een Order-object aan voor de huidige gebruiker
$order = new Order($userId);

// Verwerk acties (toevoegen, verwijderen, legen, bestelling plaatsen)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add_to_cart'])) {
            $productId = (int)$_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            $order->addToCart($productId, $quantity);
            $message = "Product succesvol toegevoegd aan winkelwagen.";
        } elseif (isset($_POST['remove'])) {
            $productId = (int)$_POST['product_id'];
            $order->removeFromCart($productId);
            $message = "Product verwijderd uit winkelwagen.";
        } elseif (isset($_POST['clear'])) {
            $order->clearCart();
            $message = "Winkelwagen geleegd.";
        } elseif (isset($_POST['checkout'])) {
            $message = $order->placeOrder();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Haal winkelwageninhoud op
$cartItems = $order->getCartItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelmandje</title>
    <link rel="stylesheet" href="webshop.css">
</head>
<body>
    <?php include_once("nav.php"); ?> 

    <h1>Winkelmandje</h1>

    <?php if (!empty($message)): ?>
        <p class="success"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (!empty($cartItems)): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Prijs</th>
                    <th>Aantal</th>
                    <th>Totaal</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td>€<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>€<?php echo number_format($item['total_price'], 2); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="remove">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form method="post">
            <button type="submit" name="clear">Winkelwagen Leegmaken</button>
            <button type="submit" name="checkout">Afrekenen</button>
        </form>
    <?php else: ?>
        <p>Je winkelwagen is leeg.</p>
    <?php endif; ?>

    <a href="products.php">Verder winkelen</a>
</body>
</html>
