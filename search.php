<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

?>

<!-- HTML Form to collect search keyword and submit it to the same page on the server -->
<div style="width: 80%; margin: auto;">
    <form name="frmSearch" method="get" action="">
        <div class="mb-3 row">
            <div class="col-12 text-center">
                <h2 class="page-title">Product Search</h2>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="keywords" class="col-sm-3 col-form-label text-sm-end">Product Title:</label>
            <div class="col-sm-9">
                <input class="form-control" name="keywords" id="keywords" type="search" />
            </div>
        </div>
      <!-- Dropdown for selecting category -->
        <div class="mb-3 row">
            <label for="category" class="col-sm-3 col-form-label text-sm-end">Category:</label>
            <div class="col-sm-9">
                <select class="form-control" name="category" id="category">
                    <option value="">All Categories</option>
                    <?php
                    include_once("mysql_conn.php");  // Include the database connection file

                    // Fetch categories from the database
                    $categoryQuery = "SELECT * FROM Category";
                    $categoryResult = $conn->query($categoryQuery);

                    if ($categoryResult->num_rows > 0) {
                        while ($categoryRow = $categoryResult->fetch_assoc()) {
                            $categoryName = $categoryRow['CatName'];
                            echo "<option value=\"$categoryName\">$categoryName</option>";
                        }
                    }
                   ?>
                </select>
            </div>
        </div>
        <!--Minimum and maximum price range-->
        <div class="mb-3 row">
            <label for="minPrice" class="col-sm-3 col-form-label text-sm-end">Min Price ($):</label>
            <div class="col-sm-3">
                <input class="form-control" name="minPrice" id="minPrice" type="number" />
            </div>
            <label for="maxPrice" class="col-sm-3 col-form-label text-sm-end">Max Price ($):</label>
            <div class="col-sm-3">
                <input class="form-control" name="maxPrice" id="maxPrice" type="number" />
            </div>
        </div>
        <!--checkbox for products on offer-->
        <div class="mb-3 row align-items-center">
            <div class="col-sm-3"></div> <!-- Empty column to align with labels -->
            <div class="col-sm-9">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="discount" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Discount
                    </label>
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
</div>



    <?php
    include_once("mysql_conn.php"); 
    // The non-empty search keyword is sent to the server
    if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
        $SearchText = $_GET['keywords'];
        $minPrice = isset($_GET['minPrice']);
        $maxPrice = isset($_GET['maxPrice']);

        //check if checkbox is checked
        $discountChecked = isset($_GET['discount'])? $_GET['discount']:0;
        // Retrieve category filter value
        $categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';


        // Retrieve list of product records with "ProductTitle" 
        // contains the keywords, price range, and occasion entered by the shopper, and display them in a table
        $qry = "SELECT * FROM product p JOIN catproduct cp ON p.ProductID = cp.ProductID
        WHERE (p.ProductTitle LIKE '%$SearchText%' OR p.ProductDesc LIKE '%$SearchText%')";

        // Add conditions based on user input
        if ($minPrice > 0) {
            $qry .= " AND Price >= $minPrice";
        }

        if ($maxPrice < PHP_INT_MAX) {
            $qry .= " AND Price <= $maxPrice";
        }
        // Add condition for category filter
        if ($categoryFilter !== '') {
           // Subquery to retrieve CategoryID based on CatName
            $categorySubquery = "SELECT CategoryID FROM Category WHERE CatName = '$categoryFilter'";
            
            // Main query to filter by CategoryID
            $qry .= " AND cp.CategoryID IN ($categorySubquery)";
        }
        // Condition for showing only discounted products
        if ($discountChecked == 1) {
            $qry .= " AND Offered = 1 AND NOW() BETWEEN OfferStartDate AND OfferEndDate";
        }

        $qry .= " ORDER BY ProductTitle";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($qry);
        // Execute the statement
        $stmt->execute();
        // Get the result
        $result = $stmt->get_result();
        $stmt->close();
        if($result->num_rows > 0){
            echo '<div class="mt-4 container">'; // Create a container for search results
            echo "<div class ='col-8'> <b>Search results for $SearchText</b> <br/>";
            while ($row = $result -> fetch_array()){
                $productTitle = urldecode($row['ProductTitle']);
                $productDescription = urldecode($row['ProductDesc']);
                $originalPrice = urldecode(number_format($row['Price'],2));
                $discountedPrice = urldecode(number_format($row['OfferedPrice'],2));
                //calculate percentage discount
                $discountPercentage = round((($originalPrice-$discountedPrice)/$originalPrice)* 100);
                $productDetails = "productDetails.php?pid=$row[ProductID]&ProductTitle=$productTitle";
      

                //if product is on offer, show the original and discounted price
              
                    // Display search results in a table
                    echo "<div class='row mb-3'>";
                    echo "<div class='col-sm-8'>";
                    echo "<p><a href=$productDetails>$row[ProductTitle]</a></p>";
                   // Display the original price
                    echo "<b><span style='text-decoration: ";
                    //if product is on offer apply strikethrough on the original price
                    if($row['Offered'] && $row['OfferStartDate'] <= date('Y-m-d') && $row['OfferEndDate'] >= date('Y-m-d')){
                        echo"line-through";
                    }
                    else{
                        //no strikethrough if the product has no offer
                        echo"none";
                    }
                    echo ";'>Price: S$$originalPrice</span></b>";

                    // Display discounted price if available
                    if ($row['Offered'] && $row['OfferStartDate'] <= date('Y-m-d') && $row['OfferEndDate'] >= date('Y-m-d')) {
                        echo "<p><b><span style='color:red; font-size:20px;'>Now S$$discountedPrice</span></b></p>";
                        echo "<p style='color:green;'>$discountPercentage% off</p>";
                    }

                    echo "</div>";
                    echo "</div>";  
                
            }
        } else {
            echo "<div class='row'>";
            echo "<div class='col-sm-8' style='padding:5px'>";
            echo "<p style='color:red;'>No records found.</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>"; // End of container
    }
    
    $conn->close();
    include("footer.php"); // Include the Page Layout footer
    ?>
