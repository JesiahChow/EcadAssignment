<?php

//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "
			 <li class='nav-item'>
		     <a class='nav-link' href='login.php'>Login</a></li>";
if (isset($_SESSION["ShopperName"])) {


    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
    $content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "
                <li class='nav-item'>
                <a class='nav-link' href='logout.php'>Logout</a></li>";


    //Display number of item in cart
    if(isset($_SESSION["NumCartItem"])){
        $content1 .= ", $_SESSION[NumCartItem] item(s) in shopping cart";
    }
}
?>

    <!-- Display a navbar which is visible before or after collapsing -->
    <nav class="navbar navbar-expand-md navbar-custom bg-custom"> 
    <div class="container-fluid">
        <!--Dynamic text display-->
        <span class="navbar-text ms-md-2" style="color:#B8860B; max-width: 80%;">
       <!-- <a class="navbar-brand" href="index.php">
            <img src="Images/logo.png" alt="Logo" class="img-fluid" style="height: 100px;">
        </a>-->
            <?php echo $content1; ?>
        </span>
        <!--Toggler/Collapsible button-->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!--
     Define a collapsible navbar -->
<nav class="navbar navbar-expand-sm bg-custom navbar-custom">
    <div class="container-fluid">
        <!--collapsible part of navbar-->
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="category.php">Product Category</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="shoppingcart.php">Shopping Cart</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="search.php">Search</a>
        </li>
      </ul>
            <!--right-justified menu items-->
        <ul class="navbar-nav ms-auto">
                <?php echo $content2; ?>
            </ul>
        </div>
    </div>
</nav>