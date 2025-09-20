<?php
session_start();

if (!isset($_SESSION['User_Id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['User_Id'];

// Get user info
$user_query = "SELECT email FROM user WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user_email = "N/A";

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user_email = mysqli_fetch_assoc($user_result)['email'];
}

date_default_timezone_set('Asia/Kathmandu');

$subscription_status = "No Subscription";
$subscription_message = "";

// Check active subscription
$sub_query = "SELECT * FROM subscription 
              WHERE user_id = $user_id 
              AND status = 'Active' 
              AND end_time >= NOW() 
              ORDER BY end_time DESC 
              LIMIT 1";
$sub_result = mysqli_query($conn, $sub_query);

if ($sub_result && mysqli_num_rows($sub_result) > 0) {
    $sub = mysqli_fetch_assoc($sub_result);
    $subscription_status = "Active (" . htmlspecialchars($sub['plan_type']) . ") until " . $sub['end_time'];
    $subscription_message = "You now have access to all the books!";
} else {
    $expired_query = "SELECT * FROM subscription 
                      WHERE user_id = $user_id 
                      ORDER BY end_time DESC LIMIT 1";
    $expired_result = mysqli_query($conn, $expired_query);
    if ($expired_result && mysqli_num_rows($expired_result) > 0) {
        $expired = mysqli_fetch_assoc($expired_result);
        $subscription_status = "Expired (" . htmlspecialchars($expired['plan_type']) . ")";
    }
}

// Log purchased book into user_history
$log_query = "
    SELECT p.book_id
    FROM payment p
    WHERE p.user_id = $user_id AND p.book_id IS NOT NULL
";
$log_result = mysqli_query($conn, $log_query);

if ($log_result && mysqli_num_rows($log_result) > 0) {
    while ($log_row = mysqli_fetch_assoc($log_result)) {
        $book_id = $log_row['book_id'];

        // Check if this book_id is already in user_history
        $check_stmt = $conn->prepare("SELECT 1 FROM user_history WHERE user_id = ? AND book_id = ?");
        $check_stmt->bind_param("ii", $user_id, $book_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        // If not found, insert into user_history
        if ($check_result->num_rows === 0) {
            $insert_stmt = $conn->prepare("INSERT INTO user_history (user_id, book_id) VALUES (?, ?)");
            $insert_stmt->bind_param("ii", $user_id, $book_id);
            $insert_stmt->execute();
            $insert_stmt->close();
        }

        $check_stmt->close();
    }
}


// Updated history query: shows books from user_history (includes both paid and subscription)
$history_query = "
    SELECT b.title, b.author, b.access_type, b.price,
           p.method, p.payment_status, p.time, p.amount
    FROM user_history uh
    JOIN book b ON uh.book_id = b.book_id
    LEFT JOIN payment p ON p.user_id = uh.user_id AND p.book_id = uh.book_id
    WHERE uh.user_id = $user_id
    ORDER BY COALESCE(p.time, uh.history_id) DESC
";
$history_result = mysqli_query($conn, $history_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📘 Download & Payment History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #F8EDE3;
            color: #4E3B31;
        }

        h2 {
            color: #5A3E36;
        }

        .history-box {
            background-color: #FAF3E0;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #5A3E36;
            color: #F8EDE3;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #FFF7ED;
        }

        .table tbody tr:nth-child(even) {
            background-color: #F4E1C1;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .btn-custom {
            background-color: #A67C52;
            color: white;
            border: none;
            padding: 10px 20px;
        }

        .btn-custom:hover {
            background-color: #8B5A2B;
            color: white;
        }

        .info-box {
            background-color: #FFF4E6;
            padding: 15px 20px;
            border-left: 5px solid #A67C52;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        a.btn-secondary {
            background-color: #8B5A2B;
            border: none;
        }

        a.btn-secondary:hover {
            background-color: #5A3E36;
        }

        .text-muted {
            font-style: italic;
            color: #7D5A50 !important;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📜 Your Download & Payment History</h2>

    <div class="info-box">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
        <p><strong>Subscription Status:</strong> <?php echo htmlspecialchars($subscription_status); ?></p>
        <?php if (!empty($subscription_message)): ?>
            <p><strong><?php echo $subscription_message; ?></strong></p>
        <?php endif; ?>
    </div>

    <div class="history-box">
        <?php if (mysqli_num_rows($history_result) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Access Type</th>
                        <th>Amount Paid</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Payment Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($history_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo ($row['access_type'] == 1) ? 'Premium' : 'Free'; ?></td>
                            <td>
                                <?php
                                if ($row['access_type'] == 1) {
                                    if (isset($row['amount']) && $row['amount'] > 0) {
                                        echo '₹' . number_format($row['amount'], 2);
                                    } else {
                                        echo 'Accessed via Subscription';
                                    }
                                } else {
                                    echo 'Free';
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['method'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_status'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['time'] ?? '-'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted text-center">
                You haven’t accessed any books yet.
            </p>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="homepage.php" class="btn btn-secondary">⬅ Back to Home</a>
        </div>
    </div>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
