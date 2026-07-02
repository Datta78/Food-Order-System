<?php
session_start();
include("../db.php");
if(!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin'){
    header("Location: admin_login.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM users WHERE role='user'");
?>

<!DOCTYPE html>
<html>
<head>
<title>All Users</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    margin: 0;
    padding: 20px;
}

/* Header / Title */
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

/* Back button */
a.back-btn {
    display: inline-block;
    margin-bottom: 15px;
    padding: 8px 15px;
    background: #1e90ff;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.3s;
}
a.back-btn:hover {
    background: #0b66c3;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 12px 10px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}

th {
    background: #ff6b6b;
    color: white;
    font-weight: bold;
}

tr:hover {
    background: #f1f1f1;
}

/* Responsive table for mobile */
@media screen and (max-width: 900px) {
    table, thead, tbody, th, td, tr {
        display: block;
    }
    th {
        text-align: left;
    }
    tr {
        margin-bottom: 15px;
        border-bottom: 2px solid #ddd;
    }
    td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }
    td::before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        font-weight: bold;
        text-align: left;
    }
}
</style>

</head>
<body>

<h2>All Users</h2>
<a href="dashboard.php" class="back-btn">Back to Dashboard</a>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Register Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
<td data-label="ID"><?php echo $row['id']; ?></td>

<td data-label="Name"><?php echo $row['name']; ?></td>

<td data-label="Email"><?php echo $row['email']; ?></td>

<td data-label="Register Date">
<?php echo date("d M Y", strtotime($row['created_at'])); ?>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>