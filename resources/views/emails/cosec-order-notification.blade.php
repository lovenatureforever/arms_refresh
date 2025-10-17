<!DOCTYPE html>
<html>
<head>
    <title>Cosec Order Notification</title>
</head>
<body>
    <h2>Cosec Order Notification</h2>
    
    <p><strong>Order Details:</strong></p>
    <ul>
        <li>Form: {{ $order->form_name }}</li>
        <li>Company: {{ $order->company_name }}</li>
        <li>Registration No: {{ $order->company_no }}</li>
        <li>Requested By: {{ $order->user }}</li>
        <li>Date: {{ $order->requested_at->format('Y-m-d H:i') }}</li>
        <li>Status: {{ ucfirst($order->status) }}</li>
    </ul>

    @if($type === 'signature_required')
        <p>This order requires your signature. Please log in to the system to sign the document.</p>
    @endif

    @if($type === 'completed')
        <p>The completed form is attached to this email.</p>
    @endif

    <p>Best regards,<br>ARMS System</p>
</body>
</html>