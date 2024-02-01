<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");

// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];
//Validate login credentials with database
include_once("mysql_conn.php");
//sql query to select email,name and password
$sql = "SELECT ShopperID,Email,Password,Name FROM Shopper WHERE Email ='$email'  ";
$result = $conn->query($sql);
//fetch the values from database
if ($result->num_rows > 0) {
    $row = $result->fetch_array();
    //if record is found and password is matched
    if ($email == $row["Email"] && $pwd == $row["Password"]) {
        //if record is found and password is matched
        $_SESSION["ShopperID"] = $row["ShopperID"];
        $_SESSION["ShopperName"] = $row["Name"];
        //for shopping cart
        $shopperID = $_SESSION["ShopperID"];
        $qry = "SELECT ShopCartID FROM ShopCart WHERE ShopperID=? AND OrderPlaced=0";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $shopperID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Active shopping cart exists
            $row = $result->fetch_array();
            $_SESSION["Cart"] = $row["ShopCartID"];

            // Count the number of uncheckout items
            $qry = "SELECT COUNT(*) AS NumItems FROM ShopCartItem WHERE ShopCartID=?";
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("i", $_SESSION["Cart"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $_SESSION["NumCartItem"] = $row["NumItems"];
        } else {
            // No active shopping cart, reset session variables
            $_SESSION["Cart"] = null;
            $_SESSION["NumCartItem"] = 0;
        }

        $stmt->close();
        $conn->close();
        header("Location:index.php");
    }

    //if email or password does not match
    elseif ($email != $row["Email"] || $pwd != $row["Password"]) {
        echo "<h3 style='color:red'>Invalid Login Credentials.</h3>";
        echo"<p>Please click <a href = 'login.php'>here</a> to login again</p>";
        return false;
    }
}

// Include the Page Layout footer
include("footer.php");
