<?php

// Add PayPal Checkout button on the shopping cart page
echo "<form method='post' action=' checkoutProcess.php'>";
echo "<p style='text-align:right; font-size:20px'>";

$options = array("Normal Delivery" => 5, "Express Delivery" => 10);

echo "Delivery Method: <select name='shipMethod'>";

$subTotal = $_SESSION["SubTotal"];

foreach ($options as $option => $cost) {
    $selected = ($option == 'Normal Delivery') ? 'selected' : ''; // Set default value as 'Normal Delivery'
    if ($subTotal >= 200) {
        $selected = ($option == 'Express Delivery') ? 'selected' : '';
    }
    echo "<option value='$option' $selected>$option ($$cost)</option>";
}

echo "</select><br />";
echo "</p>";
echo "<input type='image' style='float:right;'
						src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
echo "</form>";
