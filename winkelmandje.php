<?php
use Kocas\Git\Order;
use Kocas\Git\Product;
use Kocas\Git\User;

require_once __DIR__ . '/bootstrap.php';

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
            $size = isset($_POST['size']) ? $_POST['size'] : null; 
            $order->addToCart($productId, $quantity, $size);
            $message = "Product succesvol toegevoegd aan winkelwagen.";
        } elseif (isset($_POST['remove'])) {
            $productId = (int)$_POST['product_id'];
            $size = isset($_POST['size']) ? $_POST['size'] : null;
            $order->removeFromCart($productId, $size);
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

// Haal de gebruiker op om de balans te krijgen
$user = User::getUserById($userId); // Assuming you have a method to get the user by ID
$balance = $user ? number_format($user['balance'], 2) : '0.00'; // Format the balance to two decimals
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelmandje</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="icon" type="image/x-icon" href="uploads/paw.avif">
</head>
<body class="winkelmand-page">
    <?php include_once("nav.php"); ?> 

    <div class="cart-container">
        <h1>Winkelmandje</h1>

        <!-- Display the user's balance -->
        <div class="balance-info">
            <p><strong>Je saldo: €<?php echo htmlspecialchars($balance); ?></strong></p>
        </div>

        <?php if (!empty($message)): ?>
            <p class="success"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Cart Items -->
        <?php if (!empty($cartItems)): ?>
            <div class="containerCard">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Prijs</th>
                        <th>Aantal</th>
                        <th>Maat</th>
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
                            <td><?php echo htmlspecialchars($item['size'] ?? 'Geen maat'); ?></td>
                            <td>€<?php echo number_format($item['total_price'], 2); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="size" value="<?php echo htmlspecialchars($item['size'] ?? ''); ?>">
                                    <button type="submit" name="remove">Verwijderen</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>

            <!-- Action buttons -->
            <div class="cart-actions">
                <form method="post">
                    <button type="submit" name="clear" class="clear-btn">Winkelwagen Leegmaken</button>
                    <button type="submit" name="checkout" class="checkout-btn">Afrekenen</button>
                </form>
            </div>
        <?php else: ?>
            <p class="empty-cart">Je winkelwagen is leeg.</p>
        <?php endif; ?>

        <a href="products.php" class="continue-shopping">Verder winkelen</a>
    </div>
</body>

</html>
