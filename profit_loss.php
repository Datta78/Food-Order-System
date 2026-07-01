<?php
session_start();
include("../db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

/* DELETE ORDER */
if(isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']);

    $stmt = $conn->prepare("DELETE FROM orders WHERE id=?");
    $stmt->bind_param("i",$id);

    if($stmt->execute()){
        echo "<script>alert('Order Deleted Successfully'); window.location='all_orders.php';</script>";
    } else {
        echo "<script>alert('Delete Failed');</script>";
    }
}

$where="1";

/* FILTER */
if(isset($_GET['type'])){
$type=$_GET['type'];

/* WEEK */
if($type=="week" && !empty($_GET['start_date']) && !empty($_GET['end_date'])){
$start=$_GET['start_date'];
$end=$_GET['end_date'];

if($start <= $end){
$where="DATE(created_at) BETWEEN '$start' AND '$end'";
}
}

/* MONTH */
elseif($type=="month" && !empty($_GET['start_month']) && !empty($_GET['end_month']) && !empty($_GET['year'])){
$sm=$_GET['start_month'];
$em=$_GET['end_month'];
$year=$_GET['year'];

if($sm <= $em){
$where="MONTH(created_at) BETWEEN '$sm' AND '$em' AND YEAR(created_at)='$year'";
}
}

/* QUARTER (CUSTOM MONTH RANGE) */
elseif($type=="quarter" && !empty($_GET['q_start_month']) && !empty($_GET['q_end_month'])){
$sm=$_GET['q_start_month'];
$em=$_GET['q_end_month'];

if($sm <= $em){
$where="MONTH(created_at) BETWEEN '$sm' AND '$em'";
}
}

/* YEAR */
elseif($type=="year" && !empty($_GET['start_year']) && !empty($_GET['end_year'])){
$sy=$_GET['start_year'];
$ey=$_GET['end_year'];

$where="YEAR(created_at) BETWEEN '$sy' AND '$ey'";
}
}

$query="SELECT * FROM orders WHERE $where ORDER BY id DESC";
$result=mysqli_query($conn,$query);

/* MONTH ARRAY */
$months = [
1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",
5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",
9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec"
];
?>

<!DOCTYPE html>
<html>
<head>
<title>All Orders</title>

<style>
body{font-family:Arial;background:#f4f6f9;padding:20px;}
h2{text-align:center;}

.back{
background:#1e90ff;
color:white;
padding:8px 15px;
text-decoration:none;
border-radius:6px;
}

select,input{
padding:8px;
margin:5px;
}

button{
padding:8px 12px;
background:#28a745;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

table{
width:100%;
border-collapse:collapse;
background:white;
margin-top:20px;
}

th,td{
padding:10px;
border:1px solid #ddd;
text-align:center;
}

th{
background:#ff6b6b;
color:white;
}

/* DELETE BUTTON */
.delete-btn{
background:#dc3545;
color:white;
padding:6px 12px;
border-radius:5px;
text-decoration:none;
}

.delete-btn:hover{
background:#c82333;
}

/* PDF BUTTON */
.pdf-btn{
background:#ff6b6b;
color:white;
padding:10px 15px;
border-radius:5px;
text-decoration:none;
display:inline-block;
margin-top:10px;
}
</style>

</head>

<body>

<h2>All Orders</h2>

<a href="dashboard.php" class="back">Back to Dashboard</a>

<br><br>

<form method="GET">

<select name="type" id="reportType" onchange="showFilter()">
<option value="">Select</option>
<option value="week">Week</option>
<option value="month">Monthly</option>
<option value="quarter">Quarter</option>
<option value="year">Year</option>
</select>

<!-- WEEK -->
<span id="week" style="display:none;">
<input type="date" name="start_date">
<input type="date" name="end_date">
</span>

<!-- MONTH -->
<span id="month" style="display:none;">
<select name="start_month">
<option value="">Start Month</option>
<?php foreach($months as $num=>$name){ echo "<option value='$num'>$name</option>"; } ?>
</select>

<select name="end_month">
<option value="">End Month</option>
<?php foreach($months as $num=>$name){ echo "<option value='$num'>$name</option>"; } ?>
</select>

<input type="number" name="year" placeholder="Year">
</span>

<!-- QUARTER -->
<span id="quarter" style="display:none;">
<select name="q_start_month">
<option value="">Start Month</option>
<?php foreach($months as $num=>$name){ echo "<option value='$num'>$name</option>"; } ?>
</select>

<select name="q_end_month">
<option value="">End Month</option>
<?php foreach($months as $num=>$name){ echo "<option value='$num'>$name</option>"; } ?>
</select>
</span>

<!-- YEAR -->
<span id="year" style="display:none;">
<input type="number" name="start_year" placeholder="Start Year">
<input type="number" name="end_year" placeholder="End Year">
</span>

<button type="submit">Filter</button>

</form>

<!-- PDF BUTTON -->
<a href="download_profit_report.php?type=<?php echo $_GET['type'] ?? ''; ?>" target="_blank" class="pdf-btn">
Download PDF
</a>

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
while($row=mysqli_fetch_assoc($result)){
echo "<tr>
<td>{$row['id']}</td>
<td>{$row['food_name']}</td>
<td>₹{$row['price']}</td>
<td>{$row['quantity']}</td>
<td>₹".($row['price']*$row['quantity'])."</td>
<td>{$row['customer_name']}</td>
<td>{$row['mobile']}</td>
<td>{$row['payment_method']}</td>
<td>{$row['status']}</td>
<td>".date('d M Y',strtotime($row['created_at']))."</td>
<td>
<a href='?delete_id={$row['id']}' 
onclick=\"return confirm('Delete this order?')\" 
class='delete-btn'>Delete</a>
</td>
</tr>";
}
?>

</table>

<script>
function showFilter(){
var type=document.getElementById("reportType").value;

document.getElementById("week").style.display="none";
document.getElementById("month").style.display="none";
document.getElementById("quarter").style.display="none";
document.getElementById("year").style.display="none";

if(type=="week") document.getElementById("week").style.display="inline-block";
if(type=="month") document.getElementById("month").style.display="inline-block";
if(type=="quarter") document.getElementById("quarter").style.display="inline-block";
if(type=="year") document.getElementById("year").style.display="inline-block";
}
</script>

</body>
</html>