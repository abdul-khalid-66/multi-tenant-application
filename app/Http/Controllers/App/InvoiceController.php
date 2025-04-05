<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    // List all invoices
    public function index()
    {
        $invoices = Sale::with(['customer', 'saleDetails.product', 'saleDetails.variant'])
            ->latest()
            ->paginate(20);

        return view('app.sales.invoice.index', compact('invoices'));
    }

    // Show a specific invoice
    public function show($id)
    {
        $invoice = Sale::with(['customer', 'saleDetails.product', 'saleDetails.variant'])
            ->findOrFail($id);

        return view('app.sales.invoice.show', compact('invoice'));
    }

    // Generate PDF for an invoice

    public function generatePdf($id)
    {
        $invoice = Sale::with(['customer', 'saleDetails.product', 'saleDetails.variant'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('app.sales.invoice.pdf', compact('invoice')); // <-- "Pdf" not "PDF"

        return $pdf->download('invoice-' . $invoice->invoice_no . '.pdf');
    }
    

    // Print invoice view
    public function print($id)
    {
        $invoice = Sale::with(['customer', 'saleDetails.product', 'saleDetails.variant'])
            ->findOrFail($id);

        return view('app.sales.invoice.print', compact('invoice'));
    }

    // Search invoices
    public function search(Request $request)
    {
        $search = $request->input('search');

        $invoices = Sale::with(['customer', 'saleDetails'])
            ->where('invoice_no', 'like', "%$search%")
            ->orWhereHas('customer', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->paginate(20);

        return view('app.sales.invoice.index', compact('invoices'));
    }
}
