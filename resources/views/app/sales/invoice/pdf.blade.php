<!-- resources/views/tenant/invoices/pdf.blade.php -->
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
    <!-- Same content as print.blade.php -->
    @include('app.sales.sale_invoice_pdf')
</body>
</html>