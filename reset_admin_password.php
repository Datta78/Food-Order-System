<?php
include("../db.php");

$admin_email = "admin@gmail.com";
$new_password = "admin123"; 
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

mysqli_query($conn, "UPDATE users SET password='$hashed_password' WHERE email='$admin_email' AND role='admin'");

echo "Admin password reset successfully!";
?>