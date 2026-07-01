<?php
include("db.php"); // database connection

// Admin info
$admin_name = "Akshata"; 
$admin_email = "admin@gmail.com"; 
$admin_password = password_hash("admin123", PASSWORD_DEFAULT); // password hash

// Check if admin already exists
$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$admin_email' AND role='admin'");
if(mysqli_num_rows($check) == 0){
    // Insert admin
    mysqli_query($conn, "INSERT INTO users (name, email, password, role) 
        VALUES ('$admin_name','$admin_email','$admin_password','admin')");
    echo "Admin account created successfully!";
} else {
    echo "Admin already exists!";
}
?>