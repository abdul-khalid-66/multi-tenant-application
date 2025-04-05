<!-- resources/views/tenant/invoices/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .invoice-title { font-size: 24px; font-weight: bold; }
        .invoice-details { margin-bottom: 30px; }
        .customer-info, .business-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .summary { float: right; width: 300px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div class="business-info">
            <h1 class="invoice-title">{{ config('settings.business_name') }}</h1>
            <p>{{ config('settings.business_address') }}</p>
            <p>Phone: {{ config('settings.business_phone') }}</p>
            <p>Email: {{ config('settings.business_email') }}</p>
        </div>
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <p>#{{ $invoice->invoice_no }}</p>
            <p>Date: {{ $invoice->date->format('d M Y') }}</p>
        </div>
    </div>

    <div class="invoice-details">
        <div class="customer-info">
            <h3>Bill To:</h3>
            <p>{{ $invoice->customer->name ?? 'Walk-in Customer' }}</p>
            @if($invoice->customer)
            <p>{{ $invoice->customer->address }}</p>
            <p>Phone: {{ $invoice->customer->contact }}</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Variant</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->saleDetails as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->variant->name ?? 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ config('settings.currency_symbol') }}{{ number_format($item->sell_price, 2) }}</td>
                <td>{{ config('settings.currency_symbol') }}{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">{{ config('settings.currency_symbol') }}{{ number_format($invoice->total_amount - $invoice->tax + $invoice->discount, 2) }}</td>
            </tr>
            @if($invoice->discount > 0)
            <tr>
                <td>Discount:</td>
                <td class="text-right">-{{ config('settings.currency_symbol') }}{{ number_format($invoice->discount, 2) }}</td>
            </tr>
            @endif
            @if($invoice->tax > 0)
            <tr>
                <td>Tax:</td>
                <td class="text-right">{{ config('settings.currency_symbol') }}{{ number_format($invoice->tax, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>Total:</strong></td>
                <td class="text-right"><strong>{{ config('settings.currency_symbol') }}{{ number_format($invoice->total_amount, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>{{ config('settings.business_name') }}</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>