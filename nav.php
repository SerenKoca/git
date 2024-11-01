<link rel="stylesheet" href="webshop.css">
<link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">

<header>
        <nav class="nav">
            <div>
            <a href="index.php">Home</a> |
            <a href="products.php">Producten</a> 
            
            </div>
    
            <div class="nav_username">
            
            <a href="#" class= "user_nav"><?php echo "Username: ".$_SESSION['email']; ?></a> |
            <a href="logout.php">Log Out</a> 
            </div>

        </nav>
</header>