<?php

session_start();

?>
<?php
if(isset($_POST['adminlogin'])){
    $email= $_POST['email'];
    $password = $_POST['password'];
    
    
    
//create connection
    $conn=mysqli_connect('localhost:3307','root','','project6');
//check connection
if(!$conn){
    die("connection failed:".mysqli_connect_error());
}

//this is the code for login
$sql = "SELECT * FROM admin WHERE email='admin@gmail.com' and password='Admin@123'";
// echo $sql;
$result= mysqli_query($conn,$sql);

if(!$result) {
    echo "SQL Error: " . mysqli_error($conn);
}

if(mysqli_num_rows($result)>0){
    $admin = mysqli_fetch_assoc($result);
    $_SESSION['admin_id']= $admin['admin_id'];
    header("location:admindashboard.php");

} 
else {
    $message = "Invalid Password or Email";
    // header("location:login_form.php");
    
    ?>
   
    <script>
        // JavaScript code to display the pop-up message
        window.onload = function() {
            var message = "<?php echo $message; ?>";
            alert(message);
            window.location.href = "adminlogin.php"; 
            // return false;

            

        };
    </script>
    <?php
   
}
}
?>