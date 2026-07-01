<?php
session_start();
include("../db.php");

if(!isset($_SESSION['admin_id']) || $_SESSION['role']!='admin'){
header("Location: admin_login.php");
exit();
}

/* DELETE */
if(isset($_GET['delete'])){
$id=intval($_GET['delete']);
mysqli_query($conn,"DELETE FROM orders WHERE id=$id");
echo "<script>alert('Order Deleted');window.location='orders.php';</script>";
}

/* CONFIRM */
if(isset($_GET['confirm'])){
$id=intval($_GET['confirm']);
$msg="Your order has been confirmed by admin";

mysqli_query($conn,"UPDATE orders 
SET status='Confirmed', message='$msg', confirm_time=NOW()
WHERE id=$id");

header("Location: orders.php");
}

/* CANCEL */
if(isset($_GET['cancel'])){
$id=intval($_GET['cancel']);
$msg="Your order has been cancelled by admin";

mysqli_query($conn,"UPDATE orders 
SET status='Cancelled', message='$msg'
WHERE id=$id");

header("Location: orders.php");
}

/* FILTER */
$type = $_GET['type'] ?? "all";
$where="1";

/* WEEK = DATE RANGE */
if($type=="week"){
$start=$_GET['start_date'] ?? '';
$end=$_GET['end_date'] ?? '';

if($start && $end && $start <= $end){
$where="DATE(created_at) BETWEEN '$start' AND '$end'";
}
}

/* MONTH */
elseif($type=="month"){
$sm=$_GET['start_month'] ?? '';
$em=$_GET['end_month'] ?? '';
$year=$_GET['year'] ?? '';

if($sm && $em && $year && $sm <= $em){
$where="MONTH(created_at) BETWEEN $sm AND $em AND YEAR(created_at)='$year'";
}
}

/* QUARTER = CUSTOM MONTH */
elseif($type=="quarter"){
$sm=$_GET['start_month'] ?? '';
$em=$_GET['end_month'] ?? '';

if($sm && $em && $sm <= $em){
$where="MONTH(created_at) BETWEEN $sm AND $em";
}
}

/* YEAR */
elseif($type=="year"){
$sy=$_GET['start_year'] ?? '';
$ey=$_GET['end_year'] ?? '';

if($sy && $ey && $sy <= $ey){
$where="YEAR(created_at) BETWEEN $sy AND $ey";
}
}

$order_query="SELECT * FROM orders WHERE $where ORDER BY id DESC";
$order_result=mysqli_query($conn,$order_query);

/* MONTHS */
$months = [
1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",
5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",
9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec"
];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Orders</title>

<style>
body{font-family:Arial;background:#f4f6f9;padding:20px;}
h2{text-align:center;}
table{width:100%;border-collapse:collapse;background:white;margin-top:20px;}
th,td{padding:10px;border:1px solid #ddd;text-align:center;}
th{background:#ff6b6b;color:white;}
.btn{padding:6px 12px;color:white;text-decoration:none;border-radius:6px;}
.delete{background:#ff4d4d;}
.confirm{background:#28a745;}
.cancel{background:#f39c12;}
.back-btn{background:#1e90ff;padding:8px 15px;color:white;text-decoration:none;border-radius:6px;}
.status{padding:5px 10px;border-radius:5px;color:white;}
.pending{background:orange;}
.confirmed{background:green;}
.cancelled{background:red;}
.filter-box{text-align:center;margin:20px;}
select,input{padding:8px;margin:5px;}
button{padding:8px 12px;background:#28a745;color:white;border:none;border-radius:5px;}
</style>

</head>

<body>

<h2>All Orders</h2>

<a href="dashboard.php" class="back-btn">Back to Dashboard</a>

<div class="filter-box">

<form method="GET">

<select name="type" id="filterType" onchange="showFilter()">
<option value="all">All</option>
<option value="week" <?php if($type=="week") echo "selected"; ?>>Week</option>
<option value="month" <?php if($type=="month") echo "selected"; ?>>Monthly</option>
<option value="quarter" <?php if($type=="quarter") echo "selected"; ?>>Quarter</option>
<option value="year" <?php if($type=="year") echo "selected"; ?>>Year</option>
</select>

<!-- WEEK (DATE RANGE) -->
<input type="date" name="start_date" id="date1" value="<?php echo $_GET['start_date'] ?? ''; ?>" style="display:none;">
<input type="date" name="end_date" id="date2" value="<?php echo $_GET['end_date'] ?? ''; ?>" style="display:none;">

<!-- MONTH -->
<select name="start_month" id="month1" style="display:none;">
<option value="">Start Month</option>
<?php foreach($months as $num=>$name){
$sel = (($_GET['start_month'] ?? '')==$num) ? "selected" : "";
echo "<option value='$num' $sel>$name</option>";
} ?>
</select>

<select name="end_month" id="month2" style="display:none;">
<option value="">End Month</option>
<?php foreach($months as $num=>$name){
$sel = (($_GET['end_month'] ?? '')==$num) ? "selected" : "";
echo "<option value='$num' $sel>$name</option>";
} ?>
</select>

<input type="number" name="year" placeholder="Year" id="monthYear" value="<?php echo $_GET['year'] ?? ''; ?>" style="display:none;">

<!-- YEAR -->
<input type="number" name="start_year" placeholder="Start Year" id="year1" value="<?php echo $_GET['start_year'] ?? ''; ?>" style="display:none;">
<input type="number" name="end_year" placeholder="End Year" id="year2" value="<?php echo $_GET['end_year'] ?? ''; ?>" style="display:none;">

<button type="submit">Filter</button>

</form>

</div>

<table>

<tr>
<th>ID</th>
<th>Food</th>
<th>Price</th>
<th>Qty</th>
<th>Total</th>
<th>Customer</th>
<th>Mobile</th>
<th>Payment</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($order_result)){

$status_class="pending";
if($row['status']=="Confirmed") $status_class="confirmed";
if($row['status']=="Cancelled") $status_class="cancelled";

echo "<tr>
<td>{$row['id']}</td>
<td>{$row['food_name']}</td>
<td>₹{$row['price']}</td>
<td>{$row['quantity']}</td>
<td>₹{$row['total_amount']}</td>
<td>{$row['customer_name']}</td>
<td>{$row['mobile']}</td>
<td>{$row['payment_method']}</td>
<td><span class='status $status_class'>{$row['status']}</span></td>
<td>".date("d M Y",strtotime($row['created_at']))."</td>
<td>";

if($row['status']=="Pending"){
echo "
<a href='orders.php?confirm={$row['id']}' class='btn confirm'>Confirm</a>
<a href='orders.php?cancel={$row['id']}' class='btn cancel'>Cancel</a>
";
}

echo "
<a href='orders.php?delete={$row['id']}' class='btn delete' onclick='return confirm(\"Delete order?\")'>Delete</a>
</td>
</tr>";
}
?>

</table>

<script>
function showFilter(){

var type=document.getElementById("filterType").value;

var ids=['date1','date2','month1','month2','monthYear','year1','year2'];

ids.forEach(function(id){
document.getElementById(id).style.display="none";
});

if(type=="week"){
date1.style.display="inline";
date2.style.display="inline";
}

if(type=="month"){
month1.style.display="inline";
month2.style.display="inline";
monthYear.style.display="inline";
}

if(type=="quarter"){
month1.style.display="inline";
month2.style.display="inline";
}

if(type=="year"){
year1.style.display="inline";
year2.style.display="inline";
}
}

/* AUTO LOAD */
window.onload = function(){
showFilter();
};
</script>

</body>
</html>