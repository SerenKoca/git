<?php

// Zorg ervoor dat de juiste paden worden gebruikt voor je includes
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Customer.php");

use Kocas\Git\Customer;

$error1 = "";

if (!empty($_POST)) {
    try {
        // Maak een nieuw Customer object aan
        $customer = new Customer();
        $customer->setEmail($_POST['email']);
        $customer->setPassword($_POST['password']);

        // Probeer de klant op te slaan
        if ($customer->save()) {
            echo "Account succesvol aangemaakt!";
            // Eventueel redirect naar loginpagina:
            // header("Location: login.php");
            // exit;
        } else {
            $error1 = "Er is een fout opgetreden bij het opslaan van de gebruiker.";
        }
    } catch (Exception $e) {
        $error1 = $e->getMessage();
    }
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
</head>
<body>
    <div id="app">
        <form action="" method="post">
            <h1>Sign Up</h1>

            <div class="form form--login">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="email">
            
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password">
                
                <?php if (!empty($error1)): ?>
                    <div class="alert"><?php echo htmlspecialchars($error1); ?></div>
                <?php endif; ?>
            </div>

            <input type="submit" value="Sign Up" class="btn">

            <div><p>Al een Account? <a href="login.php">Login</a></p></div>
        </form>
    </div>
</body>
</html>
