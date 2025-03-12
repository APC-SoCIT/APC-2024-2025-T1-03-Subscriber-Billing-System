<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
</head>
<body>
    <h2>Payment Receipt</h2>
    <p>Hello {{ $userName }},</p>
    <p>You have changed your subscription, Thank you for your payment. Here are the details:</p>

    <ul>
        <li><strong>Plan:</strong> {{ $planName }}</li>
        <li><strong>Amount Paid:</strong> Php {{ number_format($amount, 2) }}</li>
        <li><strong>Reference Number:</strong> {{ $referenceNumber }}</li>
        <li><strong>Date:</strong> {{ $date }}</li>
    </ul>

    <p>If you have any questions, feel free to contact us.</p>
    <p>Thank you!</p>
</body>
</html>









// Nekeisha Elfa
