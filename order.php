<?php
use Kocas\Git\Order;

include_once(__DIR__ . "/classes/Order.php");
require_once __DIR__ . '/bootstrap.php';

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Haal gebruikersinformatie op
$userId = $_SESSION['user_id'];
$orders = Order::getOrdersByUser($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Bestellingen</title>
    <link rel="stylesheet" href="webshop.css">
</head>
<body>
    <?php include_once("nav.php"); ?> 

    <h1>Mijn Bestellingen</h1>
    <?php if (!empty($orders)): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Aantal</th>
                    <th>Maat</th> <!-- Nieuwe kolom voor maat -->
                    <th>Totale Prijs</th>
                    <th>Datum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['title']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['size'] ?? 'Geen maat'); ?></td> <!-- Weergave van de maat -->
                        <td>€<?php echo number_format($order['total_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Je hebt nog geen bestellingen geplaatst.</p>
    <?php endif; ?>
</body>
</html>
