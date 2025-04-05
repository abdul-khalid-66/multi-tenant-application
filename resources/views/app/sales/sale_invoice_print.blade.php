<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $sale->invoice_no }}</title>
    <style>
        /* Print-specific styles */
        @page {
            size: A4;
            margin: 10mm;
        }
        
        @media print {
            body {
                font-family: Arial, sans-serif;
                font-size: 12pt;
                line-height: 1.4;
                color: #000;
                -webkit-print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-after: always;
            }
            
            /* Ensure tables don't break across pages */
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
        
        /* Screen styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .company-info {
            flex: 1;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .company-logo {
            max-height: 80px;
            margin-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            background-color: #f5f5f5;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        .totals {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 0.9em;
        }
        
        .print-actions {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background: #f5f5f5;
        }
        
        .status {
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .pending {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Print actions (visible only on screen) -->
        <div class="print-actions no-print">
            <button onclick="window.print()" class="btn btn-primary">
                Print Invoice
            </button>
            <button onclick="window.close()" class="btn">
                Close Window
            </button>
        </div>

        <div class="invoice-header">
            <div class="company-info">
                @if($company['logo'])
                <img src="{{ $company['logo'] }}" class="company-logo" alt="Company Logo">
                @endif
                <h2>{{ $company['name'] }}</h2>
                <p>{{ $company['address'] }}</p>
                <p>Phone: {{ $company['phone'] }}</p>
                <p>Email: {{ $company['email'] }}</p>
            </div>
            
            <div class="invoice-info">
                <h1>INVOICE</h1>
                <p><strong>Invoice #:</strong> {{ $sale->invoice_no }}</p>
                <p><strong>Date:</strong> {{ $sale->date->format('M d, Y') }}</p>
                <p><strong>Status:</strong>
                    <span class="status {{ $sale->payment_status === 'paid' ? 'paid' : 'pending' }}">
                        {{ ucfirst($sale->payment_status) }}
                    </span>
                </p>
            </div>
        </div>

        <div class="customer-info">
            <h3>Bill To:</h3>
            <p><strong>{{ $sale->customer->name ?? 'Walk-in Customer' }}</strong></p>
            <p>{{ $sale->customer->address ?? 'N/A' }}</p>
            <p>Phone: {{ $sale->customer->contact ?? 'N/A' }}</p>
            <p>Email: {{ $sale->customer->email ?? 'N/A' }}</p>
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
                @foreach($sale->saleDetails as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->variant?->name ?? 'Standard' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->sell_price, 2) }}</td>
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
                @if($sale->discount > 0)
                <tr>
                    <td><strong>Discount:</strong></td>
                    <td>-${{ number_format($sale->discount, 2) }}</td>
                </tr>
                @endif
                @if($sale->tax > 0)
                <tr>
                    <td><strong>Tax:</strong></td>
                    <td>${{ number_format($sale->tax, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Total Amount:</strong></td>
                    <td>${{ number_format($sale->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="payment-info">
            <p><strong>Payment Method:</strong> {{ ucfirst($sale->payment_method) }}</p>
            @if($sale->notes)
            <p><strong>Notes:</strong> {{ $sale->notes }}</p>
            @endif
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>{{ $company['name'] }} | {{ $company['phone'] }} | {{ $company['email'] }}</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 200);
        };
        
        // Close window after print (optional)
        window.onafterprint = function() {
            setTimeout(function() {
                window.close();
            }, 500);
        };
    </script>
</body>
</html>