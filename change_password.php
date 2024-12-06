<?php 

use Kocas\Git\User;


require_once __DIR__ . '/bootstrap.php';

session_start(); // Start de sessie

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Verwerk het wachtwoord wijzigen formulier als het is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User();
        $user->setEmail($_SESSION['email']); // Haal het e-mailadres uit de sessie

        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Controleer of het nieuwe wachtwoord en de bevestiging hetzelfde zijn
        if ($newPassword !== $confirmPassword) {
            throw new Exception("Het nieuwe wachtwoord komt niet overeen met de bevestiging.");
        }

        // Wijzig het wachtwoord
        $user->changePassword($currentPassword, $newPassword);

        $message = "Wachtwoord succesvol gewijzigd!";
    } catch (Exception $e) {
        $message = "Fout: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord Wijzigen</title>
    <link rel="stylesheet" href="webshop.css"> <!-- Gebruik dezelfde CSS-bestand -->
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
    <link rel="icon" type="image/x-icon" href="uploads/paw.avif">
    <?php include_once("nav.php"); ?>   
</head>
<body>
    <h1>Wachtwoord Wijzigen</h1>
    
    <!-- Weergeven van een bericht bij succesvol of fout -->
    <?php if (isset($message)): ?>
        <p class="alert"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Wachtwoord wijzigingsformulier -->
    <form method="POST" action="change_password.php" class="form">
        <label for="current_password">Huidig Wachtwoord</label>
        <input type="password" name="current_password" id="current_password" required>

        <label for="new_password">Nieuw Wachtwoord</label>
        <input type="password" name="new_password" id="new_password" required>

        <label for="confirm_password">Bevestig Nieuw Wachtwoord</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <button type="submit" class="btn">Wijzig Wachtwoord</button>
    </form>

    <footer>
        <?php include_once("footer.php"); ?> 
    </footer>
</body>
</html>
