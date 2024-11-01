
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
    $error1 =true;
  }
}

?>
<html>
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
</head>
<body>

  <div id="app">
      <form action="login.php" method="post">
          <h1>Sign Up</h1>
        
          <div class="form form--login">
              <label for="email">Email</label>
              <input type="text" id="email" name="email" placeholder="email">
          
              <label for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Password">
              
              <?php if(isset($error1)): ?>
              <div class="alert">Email en wachtwoord invullen!</div>
              <?php endif; ?>
          </div>

          <input type="submit" value="Sign In" class="btn">

          <div><p>Al een Account? <a href="login.php">Login</a></p></div>
      </form>
  </div>
</body>
</html>