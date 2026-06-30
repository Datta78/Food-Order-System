<?php
session_start();
include("../db.php");

if(!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin'){
    header("Location: admin_login.php");
    exit();
}

/* Delete order */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM orders WHERE id=$id");
    echo "<script>alert('Order Deleted'); window.location='orders.php';</script>";
}


/* -------------------------
   ORDER FILTER
--------------------------*/

$order_type = isset($_GET['order_type']) ? $_GET['order_type'] : 'monthly';

if($order_type == "weekly"){
$order_query = "
SELECT o.*, f.cost_price
FROM orders o
LEFT JOIN food_items f ON o.food_name = f.food_name
WHERE YEARWEEK(o.created_at,1)=YEARWEEK(CURDATE(),1)
ORDER BY o.id DESC";
}

elseif($order_type == "quarterly"){
$order_query = "
SELECT o.*, f.cost_price
FROM orders o
LEFT JOIN food_items f ON o.food_name = f.food_name
WHERE QUARTER(o.created_at)=QUARTER(CURDATE())
AND YEAR(o.created_at)=YEAR(CURDATE())
ORDER BY o.id DESC";
}

elseif($order_type == "yearly"){
$order_query = "
SELECT o.*, f.cost_price
FROM orders o
LEFT JOIN food_items f ON o.food_name = f.food_name
WHERE YEAR(o.created_at)=YEAR(CURDATE())
ORDER BY o.id DESC";
}

else{
$order_query = "
SELECT o.*, f.cost_price
FROM orders o
LEFT JOIN food_items f ON o.food_name = f.food_name
WHERE MONTH(o.created_at)=MONTH(CURDATE())
AND YEAR(o.created_at)=YEAR(CURDATE())
ORDER BY o.id DESC";
}

$order_result = mysqli_query($conn,$order_query);



/* -------------------------
   PROFIT FILTER
--------------------------*/

$type = isset($_GET['type']) ? $_GET['type'] : 'monthly';

if($type == "weekly"){

$query = "
SELECT CONCAT(YEAR(o.created_at),' - Week ',WEEK(o.created_at)) AS period,
COUNT(o.id) AS total_orders,
SUM(o.total_amount) AS total_sales,
SUM(f.cost_price * o.quantity) AS total_cost,
SUM(o.total_amount - (f.cost_price * o.quantity)) AS total_profit
FROM orders o
LEFT JOIN food_items f ON o.food_name = f.food_name
GROUP BY YEAR(o.created_at), WEEK(o.created_at)
ORDER BY o.created_at DESC";

}

elseif($type == "quarterly"){

$query = "
SELECT CONCAT(YEAR(o.created_at),' Q',QUARTER(o.created_at)) AS period,
COUNT(o.id) AS total_orders,
SUM(o.total_amount) AS total_sales,
SUM(f.cost_price * o.quantity) AS total_cost,
SUM(o.total_amount - (f.cost_price * o.quantity)) AS total_profit
FROM orders o
LEFT JOIN food_items f ON o.food_name = f.food_name
GROUP BY YEAR(o.created_at), QUARTER(o.created_at)
ORDER BY o.created_at DESC";

}

elseif($type == "yearly"){

$query = "
SELECT YEAR(o.created_at) AS period,
COUNT(o.id) AS total_orders,
SUM(o.total_amount) AS total_sales,
SUM(f.cost_price * o.quantity) AS total_cost,
SUM(o.total_amount - (f.cost_price * o.quantity)) AS total_profit
FROM orders o
LEFT JOIN food_items f ON o.food_name = f.food_name
GROUP BY YEAR(o.created_at)
ORDER BY o.created_at DESC";

}

else{

$query = "
SELECT DATE_FORMAT(o.created_at,'%Y-%m') AS period,
COUNT(o.id) AS total_orders,
SUM(o.total_amount) AS total_sales,
SUM(f.cost_price * o.quantity) AS total_cost,
SUM(o.total_amount - (f.cost_price * o.quantity)) AS total_profit
FROM orders o
LEFT JOIN food_items f ON o.food_name = f.food_name
GROUP BY DATE_FORMAT(o.created_at,'%Y-%m')
ORDER BY o.created_at DESC";

}

$profit_result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Orders</title>

<style>

body{font-family:Arial;background:#f4f6f9;padding:20px;}

h2{text-align:center;margin-bottom:20px;}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
margin-bottom:40px;
}

th,td{
padding:12px;
border:1px solid #ddd;
text-align:center;
}

th{
background:#ff6b6b;
color:white;
}

tr:hover{
background:#f1f1f1;
}

.btn{
padding:6px 12px;
background:#ff6b6b;
color:white;
text-decoration:none;
border-radius:6px;
}

.back-btn{
background:#1e90ff;
padding:6px 12px;
color:white;
text-decoration:none;
border-radius:6px;
}

select{
padding:8px 15px;
border-radius:6px;
}

</style>

</head>

<body>

<h2>All Orders</h2>

<a href="dashboard.php" class="back-btn">Back to Dashboard</a>


<!-- ORDER FILTER -->

<form method="GET" style="text-align:center;margin:20px 0;">

<select name="order_type" onchange="this.form.submit()">

<option value="weekly" <?php if($order_type=="weekly") echo "selected"; ?>>Weekly</option>

<option value="monthly" <?php if($order_type=="monthly") echo "selected"; ?>>Monthly</option>

<option value="quarterly" <?php if($order_type=="quarterly") echo "selected"; ?>>Quarterly</option>

<option value="yearly" <?php if($order_type=="yearly") echo "selected"; ?>>Yearly</option>

</select>

</form>


<table>

<tr>
<th>ID</th>
<th>Food</th>
<th>Price</th>
<th>Qty</th>
<th>Total</th>
<th>Customer</th>
<th>Address</th>
<th>Mobile</th>
<th>Payment</th>
<th>Order Date</th>
<th>Action</th>
</tr>

<?php

if(mysqli_num_rows($order_result)>0){

while($row=mysqli_fetch_assoc($order_result)){

echo "<tr>

<td>{$row['id']}</td>
<td>{$row['food_name']}</td>
<td>₹{$row['price']}</td>
<td>{$row['quantity']}</td>
<td>₹{$row['total_amount']}</td>
<td>{$row['customer_name']}</td>
<td>{$row['address']}</td>
<td>{$row['mobile']}</td>
<td>{$row['payment_method']}</td>
<td>".date("d M Y",strtotime($row['created_at']))."</td>

<td>
<a href='orders.php?delete={$row['id']}' class='btn'
onclick='return confirm(\"Delete order?\")'>Delete</a>
</td>

</tr>";

}

}

?>

</table>



<h2>Profit / Loss Report</h2>

<form method="GET" style="text-align:center;margin-bottom:20px;">

<select name="type" onchange="this.form.submit()">

<option value="weekly" <?php if($type=="weekly") echo "selected"; ?>>Weekly</option>

<option value="monthly" <?php if($type=="monthly") echo "selected"; ?>>Monthly</option>

<option value="quarterly" <?php if($type=="quarterly") echo "selected"; ?>>Quarterly</option>

<option value="yearly" <?php if($type=="yearly") echo "selected"; ?>>Yearly</option>

</select>

</form>


<table>

<tr>
<th>Period</th>
<th>Total Orders</th>
<th>Total Sales</th>
<th>Total Cost</th>
<th>Profit / Loss</th>
</tr>

<?php

while($row=mysqli_fetch_assoc($profit_result)){

echo "<tr>

<td>{$row['period']}</td>
<td>{$row['total_orders']}</td>
<td>₹{$row['total_sales']}</td>
<td>₹{$row['total_cost']}</td>
<td>₹{$row['total_profit']}</td>

</tr>";

}

?>

</table>

</body>
</html>