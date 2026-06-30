<?php
session_start();
include("../db.php");

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        echo "<script>alert('Please fill all fields');</script>";
    } else {
        $result = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND role='admin'");
        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            if(password_verify($password, $row['password'])){
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['role'] = $row['role'];

                echo "<script>
                    alert('Admin Login Successful');
                    window.location='dashboard.php';
                </script>";
            } else {
                echo "<script>alert('Wrong password');</script>";
            }
        } else {
            echo "<script>alert('No admin found');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<style>
/* General Body */
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Container */
.container {
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    width: 400px;
    text-align: center;
}

/* Title */
.container h2 {
    margin-bottom: 30px;
    color: #333;
}

/* Inputs */
.container input[type="email"],
.container input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 16px;
    box-sizing: border-box;
}

/* Button */
.container button {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    border: none;
    border-radius: 6px;
    background: #1e90ff;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.container button:hover {
    background: #0b66c3;
}

/* Footer */
.container p {
    margin-top: 20px;
    font-size: 14px;
}

.container a {
    color: #1e90ff;
    text-decoration: none;
}

.container a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>
<div class="container">
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p>Back to <a href="../login.php">User Login</a></p>
</div>
</body>
</html>