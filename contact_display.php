<?php
include("../db.php");

/* DELETE RECORD */
if(isset($_GET['delete']))
{
$id = $_GET['delete'];

$query = "DELETE FROM contact_messages WHERE id='$id'";
$data = mysqli_query($conn,$query);

if($data)
{
echo "<script>alert('Message Deleted Successfully');</script>";
}
else
{
echo "<script>alert('Failed to Delete');</script>";
}
}

/* FETCH DATA */
$query = "SELECT * FROM contact_messages";
$data = mysqli_query($conn,$query);
$total = mysqli_num_rows($data);
?>

<!DOCTYPE html>
<html>
<head>
<title>Contact Messages</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
margin:0;
padding:0;
}

.container{
width:85%;
margin:auto;
margin-top:40px;
}

h1{
text-align:center;
margin-bottom:30px;
}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

th{
background:#333;
color:white;
padding:12px;
}

td{
padding:10px;
text-align:center;
border-bottom:1px solid #ddd;
}

tr:hover{
background:#f1f1f1;
}

.delete-btn{
background:red;
color:white;
padding:6px 12px;
text-decoration:none;
border-radius:4px;
}
.dashboard-btn{
background:#3498db;
color:white;
padding:10px 15px;
text-decoration:none;
border-radius:5px;
display:inline-block;
margin-bottom:20px;
}

.dashboard-btn:hover{
background:#2980b9;
}


.delete-btn:hover{
background:darkred;
}

</style>

</head>

<body>

<div class="container">

<h1>Contact Form Messages</h1>
<a href="dashboard.php" class="dashboard-btn">⬅ Back to Dashboard</a>
<?php
if($total != 0)
{
?>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Contact</th>
<th>Gender</th>
<th>Message</th>
<th>Operation</th>
</tr>

<?php
while($result = mysqli_fetch_assoc($data))
{
echo "
<tr>
<td>".$result['id']."</td>
<td>".$result['name']."</td>
<td>".$result['email']."</td>
<td>".$result['contact']."</td>
<td>".$result['gender']."</td>
<td>".$result['message']."</td>

<td>
<a class='delete-btn' href='?delete=".$result['id']."' onclick='return checkdelete()'>Delete</a>
</td>

</tr>
";
}
?>

</table>

<?php
}
else
{
echo "<p style='text-align:center;'>No Records Found</p>";
}
?>

</div>

<script>

function checkdelete()
{
return confirm('Are you sure you want to delete this message?');
}

</script>

</body>
</html>