<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* FILTER */
$type = $_GET['type'] ?? "all";
$where = "user_id='$user_id'";

/* WEEK (DATE RANGE) */
if($type=="week"){
    $start=$_GET['start_date'] ?? '';
    $end=$_GET['end_date'] ?? '';

    if($start && $end && $start <= $end){
        $where .= " AND DATE(created_at) BETWEEN '$start' AND '$end'";
    }
}

/* MONTH */
elseif($type=="month"){
    $sm=$_GET['start_month'] ?? '';
    $em=$_GET['end_month'] ?? '';
    $year=$_GET['year'] ?? '';

    if($sm && $em && $year && $sm <= $em){
        $where .= " AND MONTH(created_at) BETWEEN $sm AND $em AND YEAR(created_at)='$year'";
    }
}

/* QUARTER (CUSTOM MONTH) */
elseif($type=="quarter"){
    $sm=$_GET['start_month'] ?? '';
    $em=$_GET['end_month'] ?? '';

    if($sm && $em && $sm <= $em){
        $where .= " AND MONTH(created_at) BETWEEN $sm AND $em";
    }
}

/* YEAR */
elseif($type=="year"){
    $sy=$_GET['start_year'] ?? '';
    $ey=$_GET['end_year'] ?? '';

    if($sy && $ey && $sy <= $ey){
        $where .= " AND YEAR(created_at) BETWEEN $sy AND $ey";
    }
}

/* QUERY */
$query = "SELECT 
    created_at,
    SUM(total_amount) as bill_total,
    payment_method,
    status,
    GROUP_CONCAT(food_name SEPARATOR ', ') as foods,
    GROUP_CONCAT(DISTINCT message SEPARATOR ', ') as messages,
    SUM(quantity) as total_qty
FROM orders
WHERE $where
GROUP BY created_at
ORDER BY created_at DESC";

$result = mysqli_query($conn,$query);

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
<title>My Orders</title>

<style>
body{font-family:Arial;background:#f4f6f9;padding:40px;}
h2{text-align:center;margin-bottom:20px;color:#333;}
table{width:100%;border-collapse:collapse;background:white;box-shadow:0 5px 15px rgba(0,0,0,0.1);}
th,td{padding:12px;border-bottom:1px solid #ddd;text-align:center;}
th{background:#ff6b6b;color:white;font-weight:bold;}
tr:hover{background:#f1f1f1;}
.download-btn{background:#28a745;color:white;padding:6px 12px;border-radius:5px;text-decoration:none;}
.download-btn:hover{background:#1e7e34;}
.back-btn{display:inline-block;margin-bottom:15px;padding:8px 15px;background:#1e90ff;color:white;text-decoration:none;border-radius:6px;}
.status{padding:5px 10px;border-radius:5px;color:white;font-size:13px;}
.pending{background:orange;}
.confirmed{background:green;}
.cancelled{background:red;}
.message{font-size:13px;color:#333;}
.filter-box{text-align:center;margin-bottom:20px;}
select,input{padding:8px;margin:5px;}
button{padding:8px 12px;background:#28a745;color:white;border:none;border-radius:5px;}
</style>
</head>

<body>

<h2>My Order History</h2>

<a href="food.php" class="back-btn">Continue Shopping</a>

<!-- FILTER -->
<div class="filter-box">
<form method="GET">

<select name="type" id="filterType" onchange="showFilter()">
<option value="all">All</option>
<option value="week" <?php if($type=="week") echo "selected"; ?>>Week</option>
<option value="month" <?php if($type=="month") echo "selected"; ?>>Monthly</option>
<option value="quarter" <?php if($type=="quarter") echo "selected"; ?>>Quarter</option>
<option value="year" <?php if($type=="year") echo "selected"; ?>>Year</option>
</select>

<!-- WEEK -->
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
<input type="number" name="start_year" id="year1" placeholder="Start Year" value="<?php echo $_GET['start_year'] ?? ''; ?>" style="display:none;">
<input type="number" name="end_year" id="year2" placeholder="End Year" value="<?php echo $_GET['end_year'] ?? ''; ?>" style="display:none;">

<button type="submit">Filter</button>

</form>
</div>

<table>

<tr>
<th>ID</th>
<th>Food</th>
<th>Qty</th>
<th>Total</th>
<th>Payment</th>
<th>Status</th>
<th>Message</th>
<th>Date</th>
<th>Receipt</th>
</tr>

<?php
if(mysqli_num_rows($result) > 0){

$id = 1;

while($row=mysqli_fetch_assoc($result)){

$date = date("d M Y h:i A", strtotime($row['created_at']));

$status_class="pending";
if($row['status']=="Confirmed") $status_class="confirmed";
if($row['status']=="Cancelled") $status_class="cancelled";

echo "<tr>
<td>{$id}</td>
<td>{$row['foods']}</td>
<td>{$row['total_qty']}</td>
<td>₹{$row['bill_total']}</td>
<td>{$row['payment_method']}</td>
<td><span class='status $status_class'>{$row['status']}</span></td>
<td class='message'>{$row['messages']}</td>
<td>{$date}</td>
<td>
<a class='download-btn' href='download_receipt.php?date={$row['created_at']}'>
Download
</a>
</td>
</tr>";

$id++;
}

}else{
echo "<tr><td colspan='9'>No orders found</td></tr>";
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

window.onload = function(){
showFilter();
};
</script>

</body>
</html>