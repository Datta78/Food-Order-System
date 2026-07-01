<?php
session_start();
include("../db.php");

/* ADMIN LOGIN CHECK */

if(!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin'){
header("Location: admin_login.php");
exit();
}

/* ADD FOOD */

if(isset($_POST['add_food'])){

$name = $_POST['food_name'];
$price = $_POST['price'];
$description = $_POST['description'];
$category = $_POST['category'];

if(!is_dir("uploads")){
mkdir("uploads",0777,true);
}

$image_name = $_FILES['image']['name'];
$tmp_name = $_FILES['image']['tmp_name'];

$folder = "uploads/".$image_name;

if(move_uploaded_file($tmp_name,$folder)){

mysqli_query($conn,"INSERT INTO food_items(food_name,price,description,image,category)
VALUES('$name','$price','$description','$image_name','$category')");

$msg="Food Added Successfully";

}
}

/* DELETE FOOD */

if(isset($_GET['delete'])){

$id = intval($_GET['delete']);

$food = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM food_items WHERE id=$id"));

if($food){

if(file_exists("uploads/".$food['image'])){
unlink("uploads/".$food['image']);
}

mysqli_query($conn,"DELETE FROM food_items WHERE id=$id");

$msg="Food Deleted Successfully";
}
}

/* EDIT FETCH */

if(isset($_GET['edit'])){

$edit_id = $_GET['edit'];

$edit_data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM food_items WHERE id='$edit_id'"));

}

/* UPDATE FOOD */

if(isset($_POST['update_food'])){

$id = $_POST['id'];
$name = $_POST['food_name'];
$price = $_POST['price'];
$description = $_POST['description'];
$category = $_POST['category'];

mysqli_query($conn,"UPDATE food_items SET
food_name='$name',
price='$price',
description='$description',
category='$category'
WHERE id='$id'");

$msg="Food Updated Successfully";

}

/* FETCH DATA */

$food_items = mysqli_query($conn,"SELECT * FROM food_items ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>
<head>

<title>Food Items</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
padding:40px;
}

.container{
width:95%;
margin:auto;
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

h2{
margin-bottom:15px;
}

form{
background:white;
padding:20px;
border-radius:8px;
margin-bottom:30px;
box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

input,textarea,select{
width:100%;
padding:10px;
margin:10px 0;
border:1px solid #ccc;
border-radius:5px;
}

button{
background:#ff6b6b;
color:white;
padding:10px 15px;
border:none;
border-radius:5px;
cursor:pointer;
}

table{
width:100%;
border-collapse:collapse;
background:white;
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

img{
width:70px;
border-radius:6px;
}

/* DESCRIPTION WIDTH CONTROL */

td:nth-child(6){
width:300px;
word-wrap:break-word;
}

/* ACTION BUTTONS */

.action-btns{
display:flex;
justify-content:center;
gap:8px;
}

.edit{
background:#28a745;
color:white;
padding:6px 12px;
text-decoration:none;
border-radius:4px;
}

.delete{
background:#dc3545;
color:white;
padding:6px 12px;
text-decoration:none;
border-radius:4px;
}

</style>

</head>

<body>

<div class="container">

<a href="dashboard.php" class="dashboard-btn">⬅ Back to Dashboard</a>

<?php if(isset($msg)){ echo "<p style='color:green;'>$msg</p>"; } ?>

<h2><?php echo isset($edit_data) ? "Edit Food Item" : "Add Food Item"; ?></h2>

<form method="POST" enctype="multipart/form-data">

<?php if(isset($edit_data)){ ?>

<input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">

<input type="text" name="food_name" value="<?php echo $edit_data['food_name']; ?>" required>

<input type="number" name="price" value="<?php echo $edit_data['price']; ?>" required>

<select name="category" required>

<option value="Veg" <?php if($edit_data['category']=="Veg") echo "selected"; ?>>Veg</option>

<option value="Non-Veg" <?php if($edit_data['category']=="Non-Veg") echo "selected"; ?>>Non-Veg</option>

<option value="Fast Food" <?php if($edit_data['category']=="Fast Food") echo "selected"; ?>>Fast Food</option>

<option value="Drinks" <?php if($edit_data['category']=="Drinks") echo "selected"; ?>>Drinks</option>


</select>

<textarea name="description"><?php echo $edit_data['description']; ?></textarea>

<button type="submit" name="update_food">Update Food</button>

<?php } else { ?>

<input type="text" name="food_name" placeholder="Food Name" required>

<input type="number" name="price" placeholder="Price" required>

<select name="category" required>

<option value="">Select Category</option>
<option value="Veg">Veg</option>
<option value="Non-Veg">Non-Veg</option>
<option value="Fast Food">Fast Food</option>
<option value="Drinks">Drinks</option>

</select>

<textarea name="description" placeholder="Food Description"></textarea>

<input type="file" name="image" required>

<button type="submit" name="add_food">Add Food</button>

<?php } ?>

</form>

<h2>Food List</h2>

<table>

<tr>

<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Price</th>
<th>Category</th>
<th>Description</th>
<th>Action</th>

</tr>

<?php while($row=mysqli_fetch_assoc($food_items)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>
<img src="uploads/<?php echo $row['image']; ?>">
</td>

<td><?php echo $row['food_name']; ?></td>

<td>₹<?php echo $row['price']; ?></td>

<td><?php echo $row['category']; ?></td>

<td><?php echo $row['description']; ?></td>

<td>

<div class="action-btns">

<a class="edit" href="?edit=<?php echo $row['id']; ?>">Edit</a>

<a class="delete" href="?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this food?')">Delete</a>

</div>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>