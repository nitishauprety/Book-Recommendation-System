<?php
// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to make a payment.");
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complete Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .payment-container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        #card-element {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        button {
            width: 100%;
            background-color: #6772e5;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #5469d4;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Premium Access - 2000.00</h2>

        <form action="charge.php" method="post" id="payment-form">
            <div id="card-element"></div>
            <button type="submit">Pay 2000.00</button>
        </form>
    </div>

    <script>
        const stripe = Stripe('pk_test_51RKeM1P88yVPWL38XW7SQcRbnhfetYGNpdlVx0yCFBLHUwSzpb5glsTSkIfBfgTIJxkFW7UoFUT4D0kpq4iuP4Si007jjXk533'); // Your public key
        const elements = stripe.elements();
        const card = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    fontFamily: '"Segoe UI", sans-serif',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a'
                }
            }
        });
        card.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            const {token, error} = await stripe.createToken(card);
            if (error) {
                alert(error.message);
            } else {
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    </script>
</body>
</html>
