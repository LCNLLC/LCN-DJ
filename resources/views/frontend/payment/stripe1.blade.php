<!-- resources/views/frontend/payment/stripe_app.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h1>Payment Details</h1>
    <p>Payment Type: {{ $payment_type }}</p>
    <p>Combined Order ID: {{ $combined_order_id }}</p>
    <p>Amount: ${{ $amount }}</p>
    <p>User ID: {{ $user_id }}</p>
    <p>Package ID: {{ $package_id }}</p>

    <!-- Add your payment form here -->
    <form id="payment-form">
        <!-- Payment form goes here -->
        <button type="submit">Submit Payment</button>
    </form>

    <script>
        var stripe = Stripe('{{ env("STRIPE_KEY") }}');

        document.getElementById('payment-form').addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.confirmCardPayment(
                'your_client_secret', // Replace with your actual client secret
                {
                    payment_method: {
                        card: elements.getElement('card'),
                        billing_details: {
                            name: 'John Doe' // Replace with actual billing details
                        }
                    }
                }
            ).then(function(result) {
                if (result.error) {
                    // Handle errors
                    console.error(result.error);
                } else {
                    // Payment succeeded
                    console.log(result.paymentIntent);
                }
            });
        });
    </script>
</body>
</html>
