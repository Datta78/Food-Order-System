<?php
session_start();
include("db.php");
$categories = ["Veg","Non-Veg","Fast Food","Drinks"];
?>

<!DOCTYPE html>
<html>
<head>
<title>Food Menu</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
font-family:Arial;
background:#f9f9f9;
}

.container{
width:90%;
margin:auto;
margin-top:100px;
}

h2{
text-align:center;
margin:40px 0;
color:#ff6f61;
}

.category-title{
margin:40px 0 20px;
color:#333;
border-left:5px solid #ff6f61;
padding-left:10px;
}


.food-menu-container{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:30px;
}


.food-box{
background:white;
border-radius:10px;
box-shadow:0 2px 8px rgba(0,0,0,0.1);
overflow:hidden;
}

.food-box img{
width:100%;
height:200px;
object-fit:cover;
}

.food-desc{
padding:15px;
}

.food-desc h3{
margin-bottom:8px;
}

.price{
color:#ff6f61;
font-weight:bold;
margin-bottom:8px;
}

.desc{
font-size:14px;
color:#666;
margin-bottom:10px;
}

.order-btn{
background:#ff6f61;
color:white;
padding:8px 15px;
text-decoration:none;
border-radius:5px;
}

.order-btn:hover{
background:#e65c50;
}



@media(max-width:900px){
.food-menu-container{
grid-template-columns:repeat(2,1fr);
}
}

@media(max-width:600px){
.food-menu-container{
grid-template-columns:1fr;
}
}

.category-title{
margin:40px 0 20px;
color:#333;
border-left:5px solid #ff6f61;
padding-left:10px;
font-size:28px;   
font-weight:bold;
}

</style>

</head>

<body>

<?php include("menubar.php"); ?>

<div class="container">

<h2>Food Menu</h2>

<?php
foreach($categories as $cat){

$foods = mysqli_query($conn,"SELECT * FROM food_items WHERE category='$cat'");

if(mysqli_num_rows($foods)>0){
?>

<h3 class="category-title"><?php echo $cat; ?></h3>

<div class="food-menu-container">

<?php while($row=mysqli_fetch_assoc($foods)){ ?>

<div class="food-box">

<img src="admin/uploads/<?php echo $row['image']; ?>">

<div class="food-desc">

<h3><?php echo $row['food_name']; ?></h3>

<p class="price">₹<?php echo $row['price']; ?></p>

<p class="desc"><?php echo $row['description']; ?></p>

<?php
if(!isset($_SESSION['user_id'])){
?>

<a href="login.php" class="order-btn">Order Now</a>

<?php
}else{
?>

<a href="cart.php?food=<?php echo $row['food_name']; ?>&price=<?php echo $row['price']; ?>" class="order-btn">
Order Now
</a>

<?php } ?>

</div>
</div>

<?php } ?>

</div>

<?php }} ?>

</div>

</body>
</html>