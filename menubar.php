<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Navigation</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family: Arial, monospace;
}

#logo{
    position:relative; 
    top:0;
    left:0;
    display:flex;
    align-items:center;
}

#logo img{
    height:80px;
    margin-left:25px;
    border-radius:50%;
    border:1px solid black;
}

#menu{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:80px;
    background-color:green;
    z-index:9999;
    display:flex;
    align-items:center;
}

#menu ul{
    list-style:none;
    display:flex;
    flex:1;
    justify-content:space-around;
}

#menu ul li{
    position:relative;
    width:120px;
    height:80px;
    line-height:80px;
    text-align:center;
}

#menu ul li a{
    text-decoration:none;
    color:white;
    font-size:22px;
    display:block;
}

#menu ul li a:hover{
    background-color:orange;
    font-style:italic;
}

#menu ul li ul{
    position:absolute;
    top:80px;
    left:0;
    display:none;
    background-color:green;
    width:120px;
}

#menu ul li ul li{
    height:45px;
    line-height:45px;
}

#menu ul li ul li a{
    font-size:20px;
    padding:0 5px;
}

#menu ul li:hover ul{
    display:block;
}

</style>
</head>

<body>

<nav id="menu">

<div id="logo">
<img src="images/logo.png" alt="MyOnlineMeal">
</div>

<ul>

<li><a href="index.php">Home</a></li>

<li><a href="About.php">About</a></li>

<li><a href="food.php">Menu</a></li>

<li><a href="cart.php">Cart</a></li>

<li><a href="order.php">Order</a></li>
<li><a href="order_history.php">My Orders</a></li>
<li><a href="contact.php">Contact</a></li>

<li><a href="login.php">Login</a></li>
<li><a href="admin/admin_login.php">Dashboard</a></li>

</ul>

</nav>

</body>
</html>