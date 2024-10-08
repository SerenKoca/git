<?php 
session_start(); // Voeg dit toe aan het begin van de pagina

function canLogin($username, $password){
    if($username === 'seren@shop.com' && $password === "12345"){
        return true;
    }else{
        return false;
    }
}

if(!empty($_POST)){
    // Er is verzonden
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(canLogin($username, $password) === true){
        // LOGIN
        $_SESSION["username"] = $username;
        header("Location: index.php");
        exit(); // Stop verdere uitvoering na doorverwijzing
    }else{
        // ERROR
        $error = true;
    }
}
?>

<html>
<head>
    <link rel="stylesheet" href="css.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<header>
  <nav class="nav">
    <a href="#" class="loggedIn">
      <div class="user--avatar">
        <img src="https://images.unsplash.com/photo-1502980426475-b83966705988?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&s=ddcb7ec744fc63472f2d9e19362aa387" alt="">
      </div>

      <!-- Zorg dat je session_start() hebt aangeroepen voor deze code -->
      <?php if(isset($_SESSION['username'])): ?>
          <h3 class="user--name"><?php echo $_SESSION['username']; ?></h3>
      <?php else: ?>
          <h3 class="user--name">Username here</h3>
      <?php endif; ?>
    </a>
    <a href="logout.php">Log out?</a>
  </nav>    
</header>

<div id="app">
    <form action="login.php" method="post">
        <h1>Log in</h1>
      
        <?php if(isset($error)): ?>
            <div class="alert">Wachtwoord is fout!</div>
        <?php endif; ?>
      
        <div class="form form--login">
            <label for="username">Username</label>
            <input type="text" id="username" name="username">
        
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>
      
        <input type="submit" value="log in" class="btn">
    </form>
</div>

</body>
</html>