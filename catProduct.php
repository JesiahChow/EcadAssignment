<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!-- Create a container, 80% width of viewport -->
<div style='width:80%; margin:auto; padding: 20px;'>

    <!-- Display Page Header - Category's name is read 
         from the query string passed from the previous page -->
    <div class="row">
        <div class="col-12 text-center">
            <h2><?php echo "$_GET[catName]"; ?></h2>
        </div>
    </div>

    <?php 
    // Include the PHP file that establishes the database connection handle: $conn
    include_once("mysql_conn.php");

    // To Do: Starting ....
    $cid = $_GET["cid"]; // Read category ID from the query string
    // Form SQL to retrieve a list of products associated with the category ID
    $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity, p.OfferedPrice, p.Offered, p.OfferStartDate, p.OfferEndDate
            FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID WHERE cp.CategoryID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $cid); // "i" - integer
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    ?>

    <div class="row">
        <?php
        // Display each product in a card layout
        while ($row = $result->fetch_array()) {
            $product = "productDetails.php?pid=$row[ProductID]";
            $formattedPrice = number_format($row["Price"], 2);
            $formattedDiscountedPrice = number_format($row["OfferedPrice"],2);
            $img = "./Images/products/$row[ProductImage]";
            //calculate the discount percentage
            $discountPercentage = round((($formattedPrice - $formattedDiscountedPrice)/$formattedPrice) * 100);
            // Check if the product is on offer during this time
            $currentDate = date('Y-m-d');
            if ($row['Offered'] && $row['OfferStartDate'] <= $currentDate && $row['OfferEndDate'] >= $currentDate) {
                // Product is on offer, display original and discounted prices
                echo "<div class='col-md-4 mb-4'>";
                echo "<div class='card h-100'>";
                echo "<img src='$img' class='card-img-top' alt='Product Image'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'><a href='$product'>$row[ProductTitle]</a></h5>";
                echo "<p class='card-text'><s>S$ $formattedPrice</s></p>";
                echo "<p class='card-text'><span style='font-weight:bold;color:red;'>S$ $formattedDiscountedPrice</span></p>";
                //display the discount percentage
                echo" <p class='card-text' style='color:green;'>$discountPercentage% off</p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            } else {
                // Product is not on offer, display the regular price
                echo "<div class='col-md-4 mb-4'>";
                echo "<div class='card h-100'>";
                echo "<img src='$img' class='card-img-top' alt='Product Image'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'><a href='$product'>$row[ProductTitle]</a></h5>";
                echo "<p class='card-text'>Price: <span style='font-weight:bold;color:red;'>S$ $formattedPrice</span></p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
        // To Do: Ending ....

        $conn->close(); // Close the database connection
        ?>
    </div>
</div>
<?php
include("footer.php"); // Include the Page Layout footer
?>
