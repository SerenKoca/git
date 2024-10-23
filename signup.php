
<?php
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/User.php");

if (!empty($_POST)) {
  try {
    $user = new User();
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['password']);

    $user->save();
    
    
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
      <?php if(isset($_SESSION['username'])): ?>
          <h3 class="user--name"><?php echo $_SESSION['username']; ?></h3>
          <a href="logout.php">Log out</a>
          <?php else: ?>
          <h3 class="user--name">Username here</h3>
          <a href="login.php">Log in</a>
      <?php endif; ?>
    </a>
    
  </nav>    
</header>

<div id="app">
    <form action="" method="post">
        <h1>sign Up</h1>
      
       
      
        <div class="form form--login">
            <label for="email">Username</label>
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