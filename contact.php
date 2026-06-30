<?php include("db.php");  ?>
<?php
$name_error = "";
$name = "";
if(($_POST['submit']))
{
   $name     = $_POST['name'];
   $email    = $_POST['email'];
   $number   = $_POST['contact'];
   $gender   = $_POST['gender'];
   $message  = $_POST['message'];
   if(empty($name)){
    $name_error="name cannot be empthy";
   }

   $query = "INSERT INTO contact_messages (name,email,contact,gender,message) 
             VALUES('$name','$email','$number','$gender','$message')";

   $data = mysqli_query($conn, $query);

   if($data) {
    echo "<script>alert('Data inserted successfully');</script>";
} else {
    if(mysqli_errno($conn) == 1062) {
        echo "<script>alert('Duplicate Email Not Allowed');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="style.css">
     <link rel="stylesheet" href="css/contact.css">
</head>
<body>
  <?php include("menubar.php"); ?>
<div class="container">
    <div id="left">
        <h1><u>Contact Us</u></h1>

        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis harum dolor, molestias aut ad quod neque incidunt voluptatum tempore quasi eligendi consequatur impedit velit corrupti, inventore quas accusantium.</p>

        <button style="background-color: orange;"><a href="tel:+917020202926">Call Us</a></button>
       <img src="images/phonecall.png" height="350px">
    </div>

    <div class="right1">
        <div class="form" >
           <form action="#" method="POST">
                  <input type="text" placeholder="Your name?..." name="name" id="name">
                  <span style=" color:red;">
                    <?php echo $name_error ?>
                  </span>
                  <input type="text" placeholder="Your Email?..." name="email" id="email">
                  <br>
                  <input type="text" placeholder="Contact number?..."  name="contact" id="contact">
                  <br>
                  <select name="gender" id="gen"  name="gender">
                    <option >Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                  <br>
                  <br>
                  <textarea placeholder="Type Your Message Here...."  name="message"></textarea>
                  <br>
                  <input type="submit" value="submit" name="submit">
                  <br>
                  <br>
                  <p>address :101,Navi mumbai, Pin: 400001, Maharashtra,India
                    <br>
                    contact :+91 9876543210<br>
                    Email : example@example.com</p>
           </form>
        </div>
    </div>
</div>  

<?php include("footer.php"); ?>
</center>

<script>
function validateform() {

    // NAME VALIDATION
    let name = document.getElementById("name").value.trim();

    if (name === "") {
        alert(" js Name cannot be empty");
        return false;
    }

    if (name.length < 2) {
        alert("js Name cannot be too small");
        return false;
    }

    if (name.length > 40) {
        alert("js Name cannot be more than 40 characters");
        return false;
    }

    let npattern = /^[A-Za-z ]+$/;
    if (!npattern.test(name)) {
        alert("js Name can contain only alphabets and spaces");
        return false;
    }


    // EMAIL VALIDATION
    let email = document.getElementById("email").value.trim();
    let epattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
        alert("js Email cannot be empty");
        return false;
    }

    if (!epattern.test(email)) {
        alert("js Enter a valid email address");
        return false;
    }


    // CONTACT VALIDATION (FIXED)
    let contact = document.getElementById("contact").value.trim();
    let cpattern = /^[0-9]{10}$/;

    if (contact === "") {
        alert("Mobile number cannot be empty");
        return false;
    }

    if (!cpattern.test(contact)) {
        alert("Enter valid 10 digit contact number");
        return false;
    }


    // GENDER VALIDATION
    let gender = document.getElementById("gen").value;

    if (gender === "Gender") {
        alert("Please select gender");
        return false;
    }


    // MESSAGE VALIDATION
    let message = document.getElementsByName("message")[0].value.trim();

    if (message === "") {
        alert("Message cannot be empty");
        return false;
    }

    if (message.length >=200) {
        alert("Message must be at least 10 characters");
        return false;
    }

    return true;
}
</script>

</script>
</body>
</html>