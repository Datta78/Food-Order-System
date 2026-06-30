<?php
session_start();
include("db.php");

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        echo "<script>alert('Please fill all fields');</script>";
    } else {
        // फक्त user role check करतो
        $result = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND role='user'");
        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            if(password_verify($password, $row['password'])){
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['role'] = $row['role'];

                echo "<script>
                    alert('User Login Successful');
                    window.location='food.php';
                </script>";

            } else {
                echo "<script>alert('Wrong password');</script>";
            }
        } else {
            echo "<script>
                alert('No user found, please register first');
            </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>User Login</title>
<style>
body{font-family:Arial; display:flex; justify-content:center; align-items:center; height:100vh; background:#f5f5f5;}
.container{background:white; padding:40px; width:400px; border-radius:10px; text-align:center; box-shadow:0 10px 25px rgba(0,0,0,0.2);}
input, button{width:100%; padding:12px; margin:10px 0; border-radius:6px; font-size:14px;}
button{background:#ff6b6b; color:white; border:none; cursor:pointer;}
button:hover{background:#e84141;}
</style>
</head>
<body>
    <?php include("menubar.php"); ?>
    <br><br>
<div class="container">
<h2>User Login</h2>
<form method="POST">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="login">Login</button>
</form>
<p>New user? <a href="registration.php">Register here</a></p>
</div>
</body>
</html>