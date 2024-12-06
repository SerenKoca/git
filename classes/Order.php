<?php

namespace Kocas\Git;

use Kocas\Git\Db;

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
    public function addToCart($productId, $quantity, $size = null) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Maak een unieke sleutel op basis van zowel het productID als de maat (indien aanwezig)
        $cartKey = $productId . ($size ? "_$size" : "");

        // Als het product al in de winkelwagen zit, werk de hoeveelheid bij
        if (isset($_SESSION['cart'][$cartKey])) {
            $_SESSION['cart'][$cartKey]['quantity'] += (int)$quantity;
        } else {
            // Voeg het product toe met de maat (null als geen maat)
            $_SESSION['cart'][$cartKey] = [
                'product_id' => $productId,
                'quantity' => (int)$quantity,
                'size' => $size  // Sla de maat op (null als geen maat)
            ];
        }
    }
    
    // Toon de producten in de winkelwagen
    public function getCartItems() {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            return [];
        }
    
        $cartItems = [];
        foreach ($_SESSION['cart'] as $productKey => $item) {
            // De sleutel is nu productId + size, dus split het op
            $productDetails = explode("_", $productKey);
            $productId = $productDetails[0];
            $size = isset($productDetails[1]) ? $productDetails[1] : null;
            
            $product = Product::getById($productId);  // Haal productgegevens op
            
            if ($product) {
                // Controleer of prijs een numerieke waarde is
                if (isset($product['price']) && is_numeric($product['price'])) {
                    // Gebruik de opgeslagen hoeveelheid
                    $quantity = $item['quantity'];
    
                    // Bereken de totaalprijs
                    $product['quantity'] = $quantity;
                    $product['total_price'] = $product['price'] * $quantity;
    
                    // Voeg de maat toe aan de productinformatie (indien aanwezig)
                    $product['size'] = $size;
    
                    // Voeg het product toe aan de lijst
                    $cartItems[] = $product;
                } else {
                    // Foutafhandelingscode als de prijs niet geldig is
                    // Dit zou in productie-omgevingen kunnen worden gelogd voor debugging
                    continue;
                }
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
        foreach ($_SESSION['cart'] as $productKey => $item) {
            $productDetails = explode("_", $productKey);
            $productId = $productDetails[0];
            $size = isset($productDetails[1]) ? $productDetails[1] : null;
    
            $product = Product::getById($productId);
            if ($product) {
                $totalCost += $product['price'] * $item['quantity'];
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
            throw new \Exception("Onvoldoende saldo om de bestelling te plaatsen.");
        }
    
        // Trek het saldo van de gebruiker af
        $userObj = new User();
        $userObj->setId($user['id']);
        $userObj->deductBalance($totalCost);
    
        // Voeg bestelling toe aan de database
        foreach ($_SESSION['cart'] as $productKey => $item) {
            $productDetails = explode("_", $productKey);
            $productId = $productDetails[0];
            $size = isset($productDetails[1]) ? $productDetails[1] : null;
    
            $product = Product::getById($productId);
            if ($product) {
                $totalPrice = $product['price'] * $item['quantity'];
    
                try {
                    $statement = $conn->prepare("
                        INSERT INTO orders (user_id, product_id, quantity, total_price, order_date, size)
                        VALUES (:userId, :productId, :quantity, :totalPrice, NOW(), :size)
                    ");
                    $statement->bindValue(':userId', $user['id']);
                    $statement->bindValue(':productId', $productId);
                    $statement->bindValue(':quantity', $item['quantity']);
                    $statement->bindValue(':totalPrice', $totalPrice);
                    $statement->bindValue(':size', $size);
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

    public static function hasUserPurchasedProduct($userId, $productId) {
        $conn = Db::getConnection();  // Get database connection
        if (!$conn) {
            throw new \Exception("Database connection failed.");
        }

        // Prepare the query to check if the user has purchased the specific product
        $statement = $conn->prepare("
            SELECT COUNT(*) 
            FROM orders 
            WHERE user_id = :userId 
            AND product_id = :productId
        ");
        $statement->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $statement->bindValue(':productId', $productId, \PDO::PARAM_INT);
        
        $statement->execute();
        $count = $statement->fetchColumn();  // Fetch the count of orders

        return $count > 0;  // If count is greater than 0, the user has purchased the product
    }
}
?>
