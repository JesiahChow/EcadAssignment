<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style="width:60%; margin:auto;">
<!-- Display Page Header -->
<div class="row" style="padding:5px"> <!-- Start of header row -->
    <div class="col-12">
        <span class="page-title">Product Categories</span>
        <p>Select a category listed below:</p>
    </div>
</div> <!-- End of header row -->

<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// retrieve all categories
$qry = "SELECT * FROM Category order by catName";
$result = $conn ->query($qry); //execute sql and get the result

while ($row = $result -> fetch_array()){
    echo"<div class='row' style ='padding:5px'>";
//Left column - display a text link showing the category's name, 
// display category's description in a new paragraph
$catName = urlencode($row["CatName"]);
$catproduct = "productListing.php?cid=$row[CategoryID]&catName=$catName";
echo"<div class='col-8'>";//67% of row width
echo"<p><a href=$catproduct>$row[CatName]</a></p>";
echo "$row[CatDesc]";
echo"</div>";

//right column - display the cateogry's image
$img = "./Images/category/$row[CatImage]";
echo"<div class ='col-4'>";
echo "<img src='$img'/>";
echo "</div>";

echo "</div>";//end of row
}


$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
