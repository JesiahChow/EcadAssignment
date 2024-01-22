<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div style="width:80%; margin:auto;"> <!-- Container -->
<form name="frmSearch" method="get" action="">
    <div class="mb-3 row"> <!-- 1st row -->
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Product Search</span>
        </div>
    </div> <!-- End of 1st row -->
    <div class="mb-3 row"> <!-- 2nd row -->
        <label for="keywords" 
               class="col-sm-3 col-form-label">Product Title:</label>
        <div class="col-sm-6">
            <input class="form-control" name="keywords" id="keywords" 
                   type="search" />
        </div>
        <div class="col-sm-3">
            <button type="submit">Search</button>
        </div>
    </div>  <!-- End of 2nd row -->
</form>

<?php
include_once("mysql_conn.php"); 
// The non-empty search keyword is sent to server
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
    $SearchText = $_GET['keywords'];
    // To Do (DIY): Retrieve list of product records with "ProductTitle" 
	// contains the keyword entered by shopper, and display them in a table.
    $qry = "SELECT ProductID, ProductTitle,ProductDesc,Price FROM product WHERE ProductTitle LIKE '%$SearchText%' or ProductDesc LIKE '%$SearchText%' ORDER BY ProductTitle";
    $result = $conn ->query($qry);

    if($result->num_rows > 0){
    echo '<div class="mt-4 container">'; // Create a container for search results
    echo "<div class ='col-8'> <b>Search results for $SearchText</b> <br/>";
    //echo "<div class ='col-8'> <b>Search results for $SearchText</b> <br/>";
    while ($row = $result -> fetch_array()){
        $productTitle = urldecode($row['ProductTitle']);
        $productDescription = urldecode($row['ProductDesc']);
        $price = urldecode(number_format($row['Price'],2));
        $productDetails = "productDetails.php?pid=$row[ProductID]&ProductTitle=$productTitle";
        $price = "productDetails.php?pid=$row[ProductID]&Price=$price";
      
        // Display search results in a table
        echo"<div class = 'row mb-3'>";
        echo "<div class = 'col-sm-8' > ";
        echo "<p><a href=$productDetails>$row[ProductTitle]</a></p>";
        echo "Price:<b><span style='color:red';>$$row[Price]</span></b>";
        echo "</div>";
        echo "</div>";
        }
    }
    else 
    {
            echo "<div class='row'>";
            echo "<div class='col-sm-8' style='padding:5px'>";
            echo "<p style='color:red;'>No records found.</p>";
            echo "</div>";
            echo "</div>";
        }
        
    
    
     
	// To Do (DIY): End of Code
} 
$conn->close();
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>