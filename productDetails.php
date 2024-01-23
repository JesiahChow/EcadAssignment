<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div class="container" style="width: 90%; margin: auto;">

<?php 
$pid = $_GET["pid"]; // Read Product ID from query string

// Include the PHP file that establishes the database connection handle: $conn
include_once("mysql_conn.php"); 
$qry = "SELECT * FROM product WHERE ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	// "i" - integer 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Display Product information. Starting ....
while ($row = $result->fetch_array()) {
    //format the price to 2 dp
    $formattedPrice = number_format($row["Price"], 2);
    //format the discount price of product on offer to 2dp
    $formattedDiscountedPrice = number_format($row["OfferedPrice"],2);
    //calculate the discount percentage
    $discountPercentage = round((($formattedPrice - $formattedDiscountedPrice)/$formattedPrice) * 100);
    // Display page header
    // product's name is read from the "ProductTitle" column of "product" table
    echo "<div class='row'>";
    echo "<div class='col-sm-12' style='padding: 10px;'>";
    echo "<h2 class='page-title'>$row[ProductTitle]</h2>";
    echo "</div>";
    echo "</div>";

    echo "<div class='row'>";// start a new row
    // Left column - display the product's description
    echo "<div class='col-sm-8' style='padding: 10px;'>";
    echo "<p>$row[ProductDesc]</p>";

    // Left column - display the product's specification
    $qry = "SELECT s.SpecName, ps.SpecVal FROM productspec ps INNER JOIN specification s ON ps.SpecID=s.SpecID WHERE ps.ProductID=? ORDER BY ps.priority";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid); // "i" - integer
    $stmt->execute();
    $result2 = $stmt->get_result();
    $stmt->close();
    //list the specifications by list
    echo "<ul>";
    while ($row2 = $result2->fetch_array()) {
        echo "<li><strong>$row2[SpecName]:</strong> $row2[SpecVal]</li>";
    }
    echo "</ul>";
    echo "</div>";// end of left column

    // Right column - display the product's image, price, and add to cart form
    $img = "./Images/products/$row[ProductImage]";
    echo "<div class='col-sm-4 text-center' style='padding: 10px;'>";
    echo "<img src='$img' class='img-fluid' alt='Product Image'>";
    
    //if product is on offer, show the original and discounted price
    $currentDate = date('Y-m-d');
    if ($row['Offered'] && $row['OfferStartDate'] <= $currentDate && $row['OfferEndDate'] >= $currentDate){
        echo "<p><s>Price:S$ $formattedPrice</s></p>";
        echo "<p><span style='font-weight: bold; color: red; font-size:20px;'>Now S$ $formattedDiscountedPrice</span></p>";
             //display the discount percentage
             echo" <p style='color:green;'>$discountPercentage% off</p>";
    }
    else{
        // Display the product's price 
        echo "<p><strong>Price:</strong> <span style='font-weight: bold; color: red;'>S$ $formattedPrice</span></p>";
    } 
    //if product quantity is out of stock
    if($row["Quantity"] <= 0){
        echo"<div class='alert alert-danger' role='alert'>";
        echo"Out of Stock";
        echo"</div>";
    } 
    else{
          // Form for adding the product to the shopping cart
    echo "<form action='cartFunctions.php' method='post'>";
    echo "<input type='hidden' name='action' value='add'/>";
    echo "<input type='hidden' name='product_id' value='$pid'/>";
    echo "<div class='mb-3'>";
    echo "<label for='quantity' class='form-label'>Quantity:</label>";
    echo "<input type='number' name='quantity' class='form-control' value='1' min='1' max='10' required/>";
    echo "</div>";
    echo "<button type='submit' class='btn btn-primary'>Add to Cart</button>";
    echo "</form>";
    
    echo "</div>";// end of right column
    echo "</div>";// end of row
    } 
}

$conn->close(); // Close database connection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
