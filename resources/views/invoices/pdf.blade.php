<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        /* Add any styling you want for your PDF */
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; padding: 10px; text-align: left; }
    </style>
</head>
<body>
    <h1>Invoice #{{ $invoice->id }}</h1>
    <p>Client: {{ $invoice->client->name }}</p>
    <p>Total Amount: {{ $invoice->total_amount }}</p>
    <p>Date: {{ $invoice->created_at->format('Y-m-d') }}</p>

    <h3>Products</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price * $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Payments</h3>
    <table>
        <thead>
            <tr>
                <th>Payment Date</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                    <td>{{ $payment->amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Total Paid: {{ $invoice->payments->sum('amount') }}</p>
    <p>Remaining Balance: {{ $invoice->total_amount - $invoice->payments->sum('amount') }}</p>
</body>
</html>