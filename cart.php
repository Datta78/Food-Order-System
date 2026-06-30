<?php
session_start();
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = array();
}

/* Add to cart logic */
if(isset($_GET['food']) && isset($_GET['price'])){
    $food = $_GET['food'];
    $price = $_GET['price'];
    $found = false;

    foreach($_SESSION['cart'] as &$item){
        if($item['food'] == $food){
            $item['quantity']++;
            $found = true;
            break;
        }
    }

    if(!$found){
        $_SESSION['cart'][] = [
            "food" => $food,
            "price" => $price,
            "quantity" => 1
        ];
    }
}

/* Remove item */
if(isset($_GET['remove'])){
    unset($_SESSION['cart'][$_GET['remove']]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>My Cart</title>
<style>
body{font-family:Arial;background:#f9f9f9;padding:40px;}
table{width:80%;margin:auto;border-collapse:collapse;background:white;}
th,td{padding:12px;text-align:center;border:1px solid #ddd;}
th{background:#ff6b6b;color:white;}
.btn{
    background:#ff6b6b;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}
.btn:hover{background:#e84141;}
</style>
</head>
<body>

<?php include("menubar.php"); ?>
<br>
<br>
<br>
<h2 align="center">My Cart</h2>

<form method="POST" action="order.php">
<table>
<tr>
<th>Select</th>
<th>Food</th>
<th>Price</th>
<th>Qty</th>
<th>Subtotal</th>
<th>Action</th>
</tr>

<?php
if(!empty($_SESSION['cart'])){
foreach($_SESSION['cart'] as $key=>$item){
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
?>
<tr>
<td>
<input type="checkbox" name="selected[]" value="<?php echo $key; ?>">
</td>
<td><?php echo $item['food']; ?></td>
<td>₹<?php echo $item['price']; ?></td>
<td><?php echo $item['quantity']; ?></td>
<td>₹<?php echo $subtotal; ?></td>
<td>
<a href="cart.php?remove=<?php echo $key; ?>" class="btn">Remove</a>
</td>
</tr>
<?php }} ?>

<tr>
<td colspan="4"><b>Total</b></td>
<td colspan="2"><b>₹<?php echo $total; ?></b></td>
</tr>

<?php if($total>0){ ?>
<tr>
<td colspan="6">
<button type="submit" class="btn">Proceed to Order</button>
</td>
</tr>
<?php } ?>
</table>
</form>

<br>
<div align="center">
<a href="food.php" class="btn">Continue Shopping</a>
</div>

<?php include("footer.php"); ?>

</body>
</html>