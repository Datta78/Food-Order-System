<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Logout</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.logout-box {
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    text-align: center;
}

.logout-box h2 {
    color: #ff6b6b;
    margin-bottom: 20px;
}

.logout-box p {
    font-size: 16px;
    color: #555;
}

.logout-box .spinner {
    margin: 20px auto;
    width: 40px;
    height: 40px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #ff6b6b;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

/* Spin animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<script>
// Redirect to admin login after 2 seconds
setTimeout(function(){
    window.location = '../index.php';
}, 2000);
</script>
</head>
<body>
<div class="logout-box">
    <h2>Logged Out</h2>
    <p>You have been successfully logged out.</p>
    <div class="spinner"></div>
</div>
</body>
</html>