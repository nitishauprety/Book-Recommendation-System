<?php

session_start();

?>
<?php
if(isset($_POST['login'])){
    $email= $_POST['email'];
    $Pass = $_POST['password'];
    $password = md5($Pass);
    
//create connection
    $conn=mysqli_connect('localhost:3307','root','','project6');
//check connection
if(!$conn){
    die("connection failed:".mysqli_connect_error());
}

//this is the code for login
$sql = "SELECT * FROM user where email='$email' and password='$password' ";
// echo $sql;
$result= mysqli_query($conn,$sql);
if(mysqli_num_rows($result)>0){
    $user = mysqli_fetch_assoc($result);
    $_SESSION['User_Id']=$user['user_id'];
    header("location:homepage.php");

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
            window.location.href = "login.php"; 
            // return false;

            

        };
    </script>
    <?php
    // header("location:login_form.php");
}
}
?>