<?php
include("db.php");

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if(empty($name) || empty($email) || empty($password) || empty($confirm)){
        echo "<script>alert('All fields are required');</script>";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<script>alert('Invalid Email Format');</script>";
    }
    elseif(strlen($password) < 6){
        echo "<script>alert('Password must be at least 6 characters');</script>";
    }
    elseif($password != $confirm){
        echo "<script>alert('Passwords do not match');</script>";
    }
    else{

        $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($check) > 0){
            echo "<script>alert('Email already registered');</script>";
        }
        else{

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 🔥 Role default 'user'
            mysqli_query($conn,"INSERT INTO users(name,email,password,role)
            VALUES('$name','$email','$hashed_password','user')");

            echo "<script>
            alert('Registration Successful');
            window.location='login.php';
            </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>User Register</title>

<style>
body{
    font-family:Arial, sans-serif;
    background-color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

.container{
    background:white;
    padding:40px;
    width:350px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    text-align:center;
}

h2{
    margin-bottom:20px;
    color:#333;
}

input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:14px;
}

input:focus{
    border-color:#ff6b6b;
    outline:none;
}

button{
    width:100%;
    padding:12px;
    background:#00b000;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
    margin-top:10px;
}

button:hover{
    background:green;
}

a{
    color:#ff6b6b;
    text-decoration:none;
    font-weight:bold;
}

a:hover{
    text-decoration:underline;
}

p{
    margin-top:15px;
}
</style>

<script>
function validateForm(){

    let name = document.forms["regForm"]["name"].value;
    let email = document.forms["regForm"]["email"].value;
    let password = document.forms["regForm"]["password"].value;
    let confirm = document.forms["regForm"]["confirm_password"].value;

    if(name == "" || email == "" || password == "" || confirm == ""){
        alert("All fields are required");
        return false;
    }

    if(password.length < 6){
        alert("Password must be at least 6 characters");
        return false;
    }

    if(password !== confirm){
        alert("Passwords do not match");
        return false;
    }

    return true;
}
</script>

</head>
<body>

<div class="container">

<h2>User Registration</h2>

<form name="regForm" method="POST" onsubmit="return validateForm()">

<input type="text" name="name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password (min 6 characters)" required>

<input type="password" name="confirm_password" placeholder="Confirm Password" required>

<button type="submit" name="register">Register</button>

</form>

<p>Already Registered? <a href="login.php">Login Here</a></p>

</div>

</body>
</html>