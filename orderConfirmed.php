<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

// if(isset($_SESSION["OrderID"])) {	
// 	echo "<p>Checkout successful. Your order number is $_SESSION[OrderID]</p>";
// 	echo "<p>Thank you for your purchase.&nbsp;&nbsp;";
// 	echo '<a href="index.php">Continue shopping</a></p>';
// } 


if (isset($_SESSION["OrderDetails"])) {
    $orderDetails = $_SESSION["OrderDetails"];

    $orderID = $orderDetails["OrderID"];
    $shipName = $orderDetails["ShipName"];
    $shipAddress = $orderDetails["ShipAddress"];
    $shipCountry = $orderDetails["ShipCountry"];
	$shipDate = $orderDetails["DeliveryDate"];
	$shipType = $orderDetails["ShipType"];
    $itemsOrdered = $orderDetails["ItemsOrdered"]; // Access items ordered

    // Display order details
    echo "<p>Checkout successful. Your order number is $_SESSION[OrderID]</p>";
	echo "<p>Shipping Type: $shipType</p>";
    echo "<p>You will receive your order by: $shipDate</p>";

    // Display items ordered using a foreach loop
    echo "Item(s) Ordered";
    echo "<ul>";
    foreach ($itemsOrdered as $item) {
        echo "<li>{$item['name']} - Quantity: {$item['quantity']}</li>";
        // You can customize the display as needed based on your item structure
    }
    echo "</ul>";
	echo "<p>Thank you for your purchase.&nbsp;&nbsp;";
	echo '<a href="index.php">Continue shopping</a></p>';

    // Clear the order details from the session to avoid displaying them again on a refresh
    unset($_SESSION["OrderDetails"]);
} else {
    
    header("Location: index.php");
    exit;
}


include("footer.php"); // Include the Page Layout footer
?>
