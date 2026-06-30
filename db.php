<?php
$conn = mysqli_connect("localhost", "root", "", "food_order_system");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}
?>
