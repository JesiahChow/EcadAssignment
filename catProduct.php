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
    $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity
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
            $img = "./Images/products/$row[ProductImage]";
        ?>
            <div class='col-md-4 mb-4'>
                <div class='card'>
                    <img src='<?php echo $img; ?>' class='card-img-top' alt='Product Image'>
                    <div class='card-body'>
                        <h5 class='card-title'><a href='<?php echo $product; ?>'><?php echo $row["ProductTitle"]; ?></a></h5>
                        <p class='card-text'>Price: <span style='font-weight:bold;color:red;'>S$ <?php echo $formattedPrice; ?></span></p>
                    </div>
                </div>
            </div>
        <?php
        }
        // To Do: Ending ....

        $conn->close(); // Close the database connection
        ?>
    </div>
</div>
<?php
include("footer.php"); // Include the Page Layout footer
?>
