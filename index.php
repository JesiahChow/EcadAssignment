<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
?>
<!--flower image source-->
<!--https://unsplash.com/photos/pink-roses-illustration-_IpKsTK9gcE-->
<h2 style="text-align: center;">Your Number 1 Flower Shop</h2>
<img src="Images/flower.jpg" class="img-fluid" 
     style="display:block; margin:auto;"/>

</hr>

<!--products on promotion-->
<div class="product-container">
<h2 style="text-align: center; color:red">Products on Offer</h2></div>

<?php
echo"<div class='container mt-3'>";
include_once("mysql_conn.php");
//retrieve products that are on offer
$qry = "SELECT * FROM product WHERE Offered = 1 AND NOW() BETWEEN OfferStartDate AND OfferEndDate;";
$result = $conn ->query($qry); //execute sql and get the result
echo"<div class='d-flex p-3 bg-custom text-white'>";
while ($row = $result -> fetch_array()){
$productName = urlencode($row["ProductTitle"]);
$productDetails = "productDetails.php?cid=$row[ProductID]&ProductTitle=$productName";

echo "<div class='col'>";
  echo"<div class='card  h-100' style='width:400px'>";
    echo"<img class='card-img-top' src='./Images/Products/$row[ProductImage]' alt='Card image' style='width:100%''>";
    echo"<div class='card-body  d-flex flex-column'>";
     echo"<h4 class='card-title'>$row[ProductTitle]</h4>";
      //show the discounted price of the product
      if ($row['Offered'] && $row['OfferedPrice'] < $row['Price']) {
          echo "<p class='card-text' style='text-decoration: line-through;'>Original Price: $$row[Price]</p>";
          echo "<p class='card-text'>Discounted Price: $$row[OfferedPrice]</p>";
      } else {
          echo "<p class='card-text'>Price: $$row[Price]</p>";
      }
  
      echo"<a href=$productDetails class='btn btn-primary'>View Product</a>";
      echo"<br>";
    echo"</div>";
  echo"</div>";
  echo"</div>";
}
echo"</div>";
echo"</div>";
  ?>


<?php 
// Include the Page Layout footer
include("footer.php"); 
?>
