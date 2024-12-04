<head>
    <link rel="stylesheet" href="webshop.css">
    <link rel="stylesheet" href="https://use.typekit.net/xgo0awo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<header>
    <nav class="nav">
        <div>
            <a href="index.php">Home</a> |
            <a href="products.php">Producten</a> 
        </div>
        
        <!-- Search bar in the middle -->
        <div class="nav_search">
            <form method="GET" action="products.php">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </form>
        </div>

        <div class="nav_username">
            <a href="#" class="user_nav"><?php echo "Username: " . $_SESSION['email']; ?></a> |
            <a href="change_password.php">Change Password</a> |
            <a href="order.php">Order geschiedenis</a> |
            <a href="logout.php">Log Out</a>
            <a href="winkelmandje.php"><i class="fa-solid fa-cart-shopping"></i></a> 
        </div>
    </nav>
</header>



