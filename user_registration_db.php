<?php
session_start(); // Always start session at the top

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $Pass = $_POST['password'];
    $password = md5($Pass);
    $email = $_POST['email'];
    $Phone = $_POST['phone'];
    $admin_id = 1;
    $user_status = 1;
    $subscription_status = isset($_POST['subscription_status']) ? $_POST['subscription_status'] : 0;


    $error = 0;

    $message1 = $message2 = $message3 = $message4 = '';

    $username_validate = '/^[a-zA-Z ]+$/';
    $password_validate = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/';
    $email_validate = '/^\w+([\.]?\w+)*@\w+([\.]?\w+)*(\.\w{2,3})+$/';
    $phone_validate = '/^98[0-9]{8}$/';


    // ✅ Perform validations
    if (!preg_match($username_validate, $username) || empty($username)) {
        $message1 = "Please enter a valid name. ";
        $error = 1;
    }
    if (!preg_match($password_validate, $Pass) || empty($Pass)) {
        $message2 = "Password must be at least 8 characters, with upper, lower and a number. ";
        $error = 1;
    }
    if (!preg_match($email_validate, $email) || empty($email)) {
        $message3 = "Invalid email format. ";
        $error = 1;
    }
    if (!preg_match($phone_validate, $Phone) || empty($Phone)) {
        $message4 = "Phone number must start with 98, be 10 digits and non negative. ";
        $error = 1;
    }

    if ($error == 0) {
        $conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql_check_email = "SELECT * FROM user WHERE email = '$email'";
        $sql_check_phone = "SELECT * FROM user WHERE Phone = '$Phone'";

        $res_email = mysqli_query($conn, $sql_check_email);
        $res_phone = mysqli_query($conn, $sql_check_phone);

        if (mysqli_num_rows($res_email) > 0) {
            $message3 = "Email already exists. ";
            $error = 1;
        }
        if (mysqli_num_rows($res_phone) > 0) {
            $message4 = "Phone number already exists. ";
            $error = 1;
        }

        if ($error == 0) {
            $sql = "INSERT INTO user(username, email, password, Phone, admin_id, user_status, subscription_status)
                    VALUES('$username', '$email', '$password', '$Phone', '$admin_id', '$user_status', '$subscription_status')";

            if (mysqli_query($conn, $sql)) {
                $last_id = mysqli_insert_id($conn);

                if ($subscription_status == 1) {
                    // ✅ Auto-login and go to Stripe payment page
                    $_SESSION['user_id'] = $last_id;
                    echo "<script>
                        alert('Premium registration successful. Redirecting to payment...');
                        window.location.href = 'payment_form.php';
                    </script>";
                    exit;
                } else {
                    echo "<script>
                        alert('Registered successfully. Please log in.');
                        window.location.href = 'login.php';
                    </script>";
                    exit;
                }
            } else {
                echo "Error inserting user: " . mysqli_error($conn);
            }
        } else {
            $message = $message1 . $message2 . $message3 . $message4;
            echo "<script>
                alert('$message');
                window.location.href = 'registration.php';
            </script>";
        }
    } else {
        $message = $message1 . $message2 . $message3 . $message4;
        echo "<script>
            alert('$message');
            window.location.href = 'registration.php';
        </script>";
    }
}
?>
