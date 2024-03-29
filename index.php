<?php 
use Vtiful\Kernel\Format;
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
?>
<h2 style="text-align: center;">Welcome to Oxy Gift Shop. Your Number 1 Trusted Gift Shop</h2>

<!--Image slideshow using bootstrap-->
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
<div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2" class=""></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3" class="active" aria-current="true"></button>
      </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="Images/cny.webp" class="d-block w-100" alt="Chinese New Year">
    </div>
    <div class="carousel-item">
      <img src="Images/valentine.webp" class="d-block w-100" alt="Valentines Day">
    </div>
    <div class="carousel-item">
      <img src="Images/wedding.webp" class="d-block w-100" alt="Wedding">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

</hr>

<!--products on offer-->
<div class="product-container">
<h2 style="text-align: center; color:red">Products on Offer</h2></div>
 
<?php
echo"<div class='container text-center'>";
include_once("mysql_conn.php");
//retrieve products that are on offer and filter those that are currently active in this current date
$qry = "SELECT * FROM product WHERE Offered = 1 AND NOW() BETWEEN OfferStartDate AND OfferEndDate;";
$result = $conn ->query($qry); //execute sql and get the result
echo"<div class='row justify-content-center'>";
while ($row = $result -> fetch_array()){
$productName = urlencode($row["ProductTitle"]);
$productDetails = "productDetails.php?pid=$row[ProductID]&ProductTitle=$productName";

echo"<div class='card' style='width:300px'>";
    echo"<img class='card-img-top' src='./Images/Products/$row[ProductImage]' alt='Card image' style='width:100%''>";
    echo"<div class='card-body  d-flex flex-column'>";
     echo"<h5 class='card-title'>$row[ProductTitle]</h4>";
     
     //calculate the percentage discount and round it to nearest whole number
     $originalPrice = number_format($row["Price"],2);
     $discountedPrice = number_format($row["OfferedPrice"],2);
     $discountPercentage = round((($originalPrice - $discountedPrice)/$originalPrice) * 100);
      //show the discounted price of the product
      if ($row['Offered'] && $row['OfferedPrice'] < $row['Price']) {
        echo "<p><span class='badge bg-danger'>On Offer</span></p>";
          echo "<p class='card-text' style='text-decoration: line-through;'>Price:S$$originalPrice</p>";
          echo "<p class='card-text' style='color:red;font-size:20px;'><b>Now S$$discountedPrice</b></p>";
           // Display the discount percentage
        echo "<p class='card-text' style='color:green;'>$discountPercentage% off</p>";
      }
      //if product is not on offer 
      else {
          echo "<p class='card-text'>Price: $$row[Price]</p>";
      }
  
      echo"<a href=$productDetails class='btn btn-primary'>View Product</a>";
      echo"<br>";
    echo"</div>";
  echo"</div>";
}
$conn->close();
echo"</div>";
echo"</div>";
  ?>

<?php 
// Include the Page Layout footer
include("footer.php"); 
?>
