<?php

namespace Kocas\Git;

date_default_timezone_set('Europe/Amsterdam');

include_once(__DIR__ . '/Db.php');
include_once(__DIR__ . '/User.php');
include_once(__DIR__ . '/Product.php');  // Zorg ervoor dat je de Product class hebt geïmporteerd

use Kocas\Git\Db;
use Kocas\Git\User;
use Kocas\Git\Product;

class Order {
    private $userId;
    private $productId;
    private $quantity;

    public function __construct($userId, $productId = null, $quantity = null) {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    // Haal bestellingen op van een gebruiker
    public static function getOrdersByUser($userId) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("
            SELECT orders.*, products.title 
            FROM orders 
            JOIN products ON orders.product_id = products.id 
            WHERE orders.user_id = :userId
        ");
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Voeg product toe aan winkelwagen
    public function addToCart($productId, $quantity) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    // Toon de producten in de winkelwagen
    public function getCartItems() {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            return [];
        }

        $cartItems = [];
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = Product::getById($productId);  // Haal productgegevens op
            if ($product) {
                $product['quantity'] = $quantity;
                $product['total_price'] = $product['price'] * $quantity;
                $cartItems[] = $product;
            }
        }
        return $cartItems;
    }

    // Plaats een bestelling
    public function placeOrder() {

        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            throw new \Exception("Je winkelwagen is leeg.");
        }
    
        $conn = Db::getConnection();
        if (!$conn) {
            throw new \Exception("Verbinding met de database mislukt.");
        }
    
        $totalCost = 0;
    
        // Bereken het totaalbedrag van de bestelling
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = Product::getById($productId);
            if ($product) {
                $totalCost += $product['price'] * $quantity;
            } else {
                echo "Product met ID $productId niet gevonden.<br>";
            }
        }
    
        // Haal de gebruiker op
        $user = User::getUserByEmail($_SESSION['email']);
        if (!$user) {
            throw new \Exception("Gebruiker niet gevonden.");
        }
    
    
        // Controleer of de gebruiker voldoende saldo heeft
        if ($user['balance'] < $totalCost) {
            throw new Exception("Onvoldoende saldo om de bestelling te plaatsen.");
        }
    
        // Trek het saldo van de gebruiker af
        $userObj = new User();
        $userObj->setId($user['id']);
        $userObj->deductBalance($totalCost);
    
       
    
        // Voeg bestelling toe aan de database
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = Product::getById($productId);
            if ($product) {
                $totalPrice = $product['price'] * $quantity;
    
                try {
                    $statement = $conn->prepare("
                        INSERT INTO orders (user_id, product_id, quantity, total_price, order_date)
                        VALUES (:userId, :productId, :quantity, :totalPrice, NOW())
                    ");
                    $statement->bindValue(':userId', $user['id']);
                    $statement->bindValue(':productId', $productId);
                    $statement->bindValue(':quantity', $quantity);
                    $statement->bindValue(':totalPrice', $totalPrice);

                    $statement->execute();
                
                    
                } catch (\Exception $e) {
                    echo "Fout tijdens het toevoegen van de bestelling: " . $e->getMessage() . "<br>";
                }
            }
        }
    
        // Winkelwagen legen
        $this->clearCart();
    
        return "Bestelling succesvol geplaatst.";
    }
    
    
    
    public function removeFromCart($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            // Verwijder het product volledig uit de winkelwagen
            unset($_SESSION['cart'][$productId]);
        } else {
            throw new \Exception("Product niet gevonden in winkelwagen.");
        }
    }
    

    // Winkelwagen legen
    public function clearCart() {
        $_SESSION['cart'] = [];
    }
}
?>
