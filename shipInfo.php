<?php

// Add PayPal Checkout button on the shopping cart page
echo "<form method='post' action=' checkoutProcess.php'>";
echo "<p style='text-align:right; font-size:20px'>";

$options = array("Normal Delivery", "Express Delivery");

echo "Delivery Method: <input type='text' name='shipMethod' value='Normal Delivery'><br />";
echo "</p>";
echo "<input type='image' style='float:right;'
						src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
echo "</form>";
?>	