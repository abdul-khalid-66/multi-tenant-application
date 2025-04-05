<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $sale->invoice_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .invoice-header { margin-bottom: 20px; }
        .company-info, .customer-info, .invoice-info { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { text-align: left; background-color: #f3f4f6; padding: 8px; }
        td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
        .totals { float: right; width: 300px; }
        .footer { margin-top: 50px; font-size: 0.8em; text-align: center; }
        .status { padding: 3px 8px; border-radius: 9999px; font-size: 0.8em; }
        .paid { background-color: #d1fae5; color: #065f46; }
        .pending { background-color: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice #{{ $sale->invoice_no }}</h1>
        <p>Date: {{ $sale->date->format('M d, Y h:i A') }}</p>
    </div>

    <div class="company-info">
        <h2>{{ $company['name'] }}</h2>
        <p>{{ $company['address'] }}</p>
        <p>Phone: {{ $company['phone'] }}</p>
        <p>Email: {{ $company['email'] }}</p>
    </div>

    <div class="customer-info">
        <h3>Bill To:</h3>
        <p><strong>{{ $sale->customer->name }}</strong></p>
        <p>{{ $sale->customer->address ?? 'N/A' }}</p>
        <p>Phone: {{ $sale->customer->contact }}</p>
        <p>Email: {{ $sale->customer->email ?? 'N/A' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Variant</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->saleDetails as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->variant?->name ?? 'N/A' }}</td>
                <td>${{ number_format($item->sell_price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->unit ?? 'pcs' }}</td>
                <td>${{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>${{ number_format($sale->total_amount + $sale->discount - $sale->tax, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Discount:</strong></td>
                <td>-${{ number_format($sale->discount, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Tax:</strong></td>
                <td>${{ number_format($sale->tax, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td>${{ number_format($sale->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="payment-info">
        <p><strong>Payment Method:</strong> {{ ucfirst($sale->payment_method) }}</p>
        <p><strong>Status:</strong> 
            <span class="status {{ $sale->payment_status === 'paid' ? 'paid' : 'pending' }}">
                {{ ucfirst($sale->payment_status) }}
            </span>
        </p>
    </div>

    @if($sale->notes)
    <div class="notes">
        <h3>Notes:</h3>
        <p>{{ $sale->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>{{ $company['name'] }}</p>
    </div>
</body>
</html>