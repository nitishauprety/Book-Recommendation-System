<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch payment and subscription data
$sql = "SELECT 
            p.payment_id, 
            p.method, 
            p.payment_status, 
            p.time, 
            p.amount, 
            u.username, 
            b.title AS book_title,
            s.plan_type,
            s.start_time,
            s.end_time
        FROM payment p
        LEFT JOIN user u ON p.user_id = u.user_id
        LEFT JOIN book b ON p.book_id = b.book_id
        LEFT JOIN subscription s ON p.payment_id = s.payment_id";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Details - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #fff7f1;
            font-family: "Poppins", sans-serif;
        }
        .sidebar {
            background-color: #ffcad4;
            height: 100vh;
            padding-top: 30px;
        }
        .sidebar a {
            color: #051821;
            font-weight: bold;
            display: block;
            padding: 15px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #f4a8ae;
        }
        .dashboard-header {
            background-color: #ffcad4;
            color: #051821;
            padding: 20px;
            border-radius: 12px;
        }
        .card-custom {
            background-color: #ffe5ec;
            border-radius: 12px;
        }
        .payment-table {
            background-color: #ffe5ec;
            border-radius: 12px;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 sidebar">
            <h2 class="text-center">📚 Book Haven</h2>
            <a href="admindashboard.php">Dashboard</a>
            <a href="manage_users.php"><i class="fas fa-user"></i> User Details</a>
            <a href="genre_display_db.php"><i class="fas fa-plus-circle"></i> Add Genre</a>
            <a href="addbooks.php"><i class="fas fa-plus-circle"></i> Add Books</a>
            <a href="displaybooks_db.php"><i class="fas fa-check-circle"></i> Book Details</a>
            <a href="payment_details.php"><i class="fas fa-check-circle"></i> Payment Details</a>
            <a href="adminlogout.php" onclick="return confirm('Are you sure you want to logout?')"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>

        <!-- Main Section -->
        <main class="col-md-10">
            <div class="dashboard-header mb-4">
                <h3>📄 Payment and Subscription Details</h3>
            </div>

            <div class="payment-table">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Payment ID</th>
                            <th>User</th>
                            <th>Book</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Time</th>
                            <th>Amount (Rs.)</th>
                            <th>Plan Type</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['payment_id'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['username'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['book_title'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['method'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['payment_status'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['time'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['amount'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['plan_type'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['start_time'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['end_time'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
</body>
</html>
