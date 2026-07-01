<?php
session_start();
if(!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin'){
    header("Location: admin_login.php");
    exit();
}

include("../db.php");

/* COUNT FUNCTION */
function getCount($conn, $query){
    $res = mysqli_query($conn, $query);
    if($res){
        $data = mysqli_fetch_assoc($res);
        return $data['total'] ? $data['total'] : 0;
    }
    return 0;
}

/* STATS */
$totalUsers = getCount($conn,"SELECT COUNT(*) AS total FROM users");
$totalOrders = getCount($conn,"SELECT COUNT(*) AS total FROM orders");
$totalFood = getCount($conn,"SELECT COUNT(*) AS total FROM food_items");
$totalContacts = getCount($conn,"SELECT COUNT(*) AS total FROM contact_messages");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<!-- ✅ FONT AWESOME (for logout icon) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
    font-family: Arial;
    margin:0;
}

/* HEADER */
header{
    background-color:green;
    color:white;
    padding:25px 0;
    text-align:center;
    box-shadow:0 4px 10px rgba(0,0,0,0.2);
}

/* CONTAINER */
.container{
    width:1000px;
    margin:auto;
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:25px;
    padding:40px 20px;
}

/* CARDS */
.card{
    padding:25px 20px;
    height:170px;
    text-align:center;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-6px);
}

/* TITLE */
.card h2{
    margin:15px 0;
    font-size:20px;
    color:#2a2a2a;
}

/* VALUE */
.card p{
    font-size:28px;
    margin:10px 0;
    color:black;
    font-weight:bold;
}

/* BUTTON */
.card a{
    display:inline-block;
    margin-top:12px;
    padding:10px 18px;
    background:#FF7070;
    color:white;
    border-radius:6px;
    text-decoration:none;
}

.card a:hover{
    background-color:red;
}

/* LOGOUT SPECIAL */
.card:last-child{
    background:#fff5f5;
}

.card:last-child a{
    background:#e74c3c;
}

.card:last-child a:hover{
    background:#c0392b;
}

</style>
</head>

<body>

<header>
<h1>Welcome Admin: <?php echo $_SESSION['admin_name']; ?></h1>
</header>

<div class="container">

<!-- USERS -->
<div class="card">
<h2>Total Users</h2>
<p><?php echo $totalUsers; ?></p>
<a href="users.php">Manage Users</a>
</div>

<!-- ORDERS -->
<div class="card">
<h2>Total Orders</h2>
<p><?php echo $totalOrders; ?></p>
<a href="orders.php">Manage Orders</a>
</div>

<!-- FOOD -->
<div class="card">
<h2>Total Food</h2>
<p><?php echo $totalFood; ?></p>
<a href="food_items.php">Manage Food</a>
</div>

<!-- CONTACT -->
<div class="card">
<h2>Total Contact</h2>
<p><?php echo $totalContacts; ?></p>
<a href="contact_display.php">View Messages</a>
</div>

<!-- REPORT -->
<div class="card">
<h2>Profit / Loss Report</h2>
<p>View Report</p>
<a href="profit_loss.php">Open Report</a>
</div>

<!-- FULL REPORT -->
<div class="card">
<h2>Full Report</h2>
<p>All Details</p>
<a href="report.php">View Report</a>
</div>

<!-- LOGOUT -->
<div class="card">
<h2>Logout</h2>
<a href="logout.php" onclick="return confirm('Are you sure to logout?')">
<i class="fa-solid fa-right-from-bracket"></i> Logout
</a>
</div>

</div>

</body>
</html>