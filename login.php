<?php
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/User.php");
session_start(); // Voeg dit toe aan het begin van de pagina

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    
    try {
        if ($user->canLogin($email, $password)) {
            $_SESSION["loggedin"] = true;
            $_SESSION["email"] = $email;
            header("Location: index.php");
            exit; // Beëindig het script na het sturen van een header
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
    <title>Document</title>
    <link rel="stylesheet" href="webshop.css">
</head>
<body>

<header>
  <nav class="nav">
    <a href="#" class="loggedIn">
      <div class="user--avatar">
        <!--<img src="https://images.unsplash.com/photo-1502980426475-b83966705988?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&s=ddcb7ec744fc63472f2d9e19362aa387" alt="">-->
      </div>

      <!-- Zorg dat je session_start() hebt aangeroepen voor deze code -->
      <?php if(isset($_SESSION['email'])): ?>
          <h3 class="user--name"><?php echo $_SESSION['email']; ?></h3>
          <a href="logout.php">Log out</a>
          <?php else: ?>
          <h3 class="user--name">Username here</h3>
          <a href="signup.php">Sign Up</a>
      <?php endif; ?>
    </a>
    
  </nav>    
</header>

<div id="app">
    <form action="login.php" method="post">
        <h1>Log in</h1>
      
       
      
        <div class="form form--login">
            <label for="email">Email</label>
            <input type="text" id="email" name="email">
        
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>

        <?php if(isset($error)): ?>
            <div class="alert">Wachtwoord is fout!</div>
        <?php endif; ?>
      
        <input type="submit" value="log in" class="btn">
    </form>
</div>

</body>
</html>