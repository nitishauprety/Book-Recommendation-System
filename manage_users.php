<?php
// Connect to the database
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check for delete action
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    if ($_GET['action'] == 'delete') {
        // Delete the user from the database
        $sql_delete = "DELETE FROM user WHERE user_id = $user_id";
        mysqli_query($conn, $sql_delete);
    }
}

// Fetch user details from the database
$sql = "SELECT user_id, username, email, phone, user_status, subscription_status FROM user ORDER BY user_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5e6d7; /* Light beige background */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #5c3d2e; /* Dark brown text */
        }
        h2 {
            color: #8b4513; /* Dark brown */
        }
        .navbar {
            background-color: #a67c52; /* Light brown navbar */
        }
        .navbar-brand, .nav-link {
            color: #fff;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #fff;
        }
        .btn-danger {
            background-color: #b85c5c; /* Reddish brown for the delete button */
            border-color: #8b4513;
        }
        .btn-danger:hover {
            background-color: #8b4513; /* Dark brown on hover */
        }
        table {
            background-color: #fffaf0; /* Soft off-white for table background */
            border-radius: 8px;
        }
        th, td {
            color: #5c3d2e; /* Dark brown text in table */
            vertical-align: middle;
        }
        footer {
            background-color: #a67c52; /* Light brown footer */
            color: #fff;
        }
    </style>
</head>
<body>

<!-- Admin Panel Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admindashboard.php">Back</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- User Details Table -->
<div class="container mt-5">
    <h2 class="text-center mb-4">User Details</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">User ID</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Status</th>
                <th scope="col">Subscription Status</th>
                <th scope="col">User Type</th> <!-- New column for User Type -->
                <!-- <th scope="col">Actions</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $user_id = $row['user_id'];
                    $username = $row['username'];
                    $email = $row['email'];
                    $phone = $row['phone'];
                    $user_status = ($row['user_status'] == 1) ? "Active" : "Inactive";
                    $subscription_status = ($row['subscription_status'] == 1) ? "Subscribed" : "Not Subscribed";
                    // Determine user type based on subscription status
                    $user_type = ($row['subscription_status'] == 1) ? "Premium" : "Normal";

                    echo "<tr>
                            <td>{$user_id}</td>
                            <td>{$username}</td>
                            <td>{$email}</td>
                            <td>{$phone}</td>
                            <td>{$user_status}</td>
                            <td>{$subscription_status}</td>
                            <td>{$user_type}</td> <!-- Display User Type -->
                           
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Footer -->
<footer class="text-center py-3 mt-5">
    <p>&copy; 2025 Book Haven | Admin Panel</p>
</footer>

</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
