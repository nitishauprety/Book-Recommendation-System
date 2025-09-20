<?php
session_start();

if (!isset($_SESSION["User_Id"])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
if ($book_id <= 0) {
    echo "<script>alert('Invalid book selected.'); window.location.href='homepage.php';</script>";
    exit();
}

$sql = "SELECT title, price, admin_id FROM book WHERE book_id = $book_id";
$result = mysqli_query($conn, $sql);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    echo "<script>alert('Book not found.'); window.location.href='homepage.php';</script>";
    exit();
}

$_SESSION['premium_book_id'] = $book_id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay for <?php echo htmlspecialchars($book['title']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f2f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .payment-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
        }

        p {
            margin: 8px 0 20px;
            color: #555;
            font-size: 16px;
        }

        #card-element {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #fafafa;
            margin-bottom: 20px;
        }

        button {
            background: #5469d4;
            color: white;
            border: none;
            padding: 12px 0;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #4253c3;
        }

        @media (max-width: 480px) {
            .payment-container {
                padding: 20px;
                width: 90%;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
<div class="payment-container">
    <h2>Buy "<?php echo htmlspecialchars($book['title']); ?>"</h2>
    <p><strong>Price:</strong> Rs. <?php echo htmlspecialchars($book['price']); ?></p>

    <form id="payment-form">
        <div id="card-element"></div>
        <button type="submit">Pay Now</button>
    </form>
</div>

<script>
    const stripe = Stripe('pk_test_51RKeM1P88yVPWL38XW7SQcRbnhfetYGNpdlVx0yCFBLHUwSzpb5glsTSkIfBfgTIJxkFW7UoFUT4D0kpq4iuP4Si007jjXk533');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('payment-form');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const response = await fetch('checkout.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                book_id: <?php echo $book_id; ?>,
                admin_id: <?php echo $book['admin_id']; ?>
            })
        });

        const data = await response.json();

        if (!data.success) {
            alert("Payment failed: " + data.message);
            return;
        }

        const result = await stripe.confirmCardPayment(data.clientSecret, {
            payment_method: { card: card }
        });

        if (result.error) {
            alert("Payment failed: " + result.error.message);
        } else if (result.paymentIntent.status === 'succeeded') {
            await fetch('record_payment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    method: "Stripe",
                    payment_status: "Completed",
                    user_id: <?php echo $_SESSION["User_Id"]; ?>,
                    admin_id: <?php echo $book['admin_id']; ?>,
                    book_id: <?php echo $book_id; ?>,
                    amount: <?php echo intval($book['price']) ?>,
                    payment_intent_id: result.paymentIntent.id
                })
            });

            alert("Payment successful!");
            window.location.href = "payment-success.php";
        }
    });
</script>
</body>
</html>

