<?php

use Web\XD\User;
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Admin.php"); // Zorg ervoor dat je de Admin klasse laadt
session_start(); // Start de sessie

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new Web\XD\User();
    $user->setEmail($email); // Stel het e-mailadres in

    try {
        if ($user->canLogin($email, $password)) {
            $_SESSION["loggedin"] = true;
            $_SESSION["email"] = $email;

            // Maak een Admin object om te controleren of het een admin is
            $admin = new Web\XD\Admin(); // Maak een Admin object aan

            // Stel het e-mail in voor de admin om toegang te krijgen
            $admin->setEmail($email);

            // Controleer of de gebruiker een admin is
            if ($admin->isAdmin()) {
                $_SESSION["is_admin"] = true; // Zet de sessie-variabele voor admin-rechten
                header("Location: admin.php");
                exit;
            } else {
                $_SESSION["is_admin"] = false; // Zet de sessie-variabele voor reguliere gebruiker
                header("Location: index.php");
                exit;
            }

        } else {
            $error = true;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<html>
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
</head>
<body>

<div id="app">
    <form action="login.php" method="post">
        <h1>Log in</h1>
      
       
      
        <div class="form form--login">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="email">
        
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password">
        </div>

        <?php if(isset($error)): ?>
            <div class="alert">Wachtwoord is fout!</div>
        <?php endif; ?>
      
        <input type="submit" value="log in" class="btn">
        <div><p>Nog geen Account? <a href="signup.php">Sign Up</a></p></div>
    </form>
</div>

</body>
</html>