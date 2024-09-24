<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Invoice;
class InvoiceController extends Controller
{
    //


    public function generatePDF(Invoice $invoice)
    {
        // Load the view for the invoice, pass the invoice data
        $pdf = PDF::loadView('invoices.pdf', ['invoice' => $invoice]);

        // Stream the generated PDF or download it
        return $pdf->download('invoice-' . $invoice->id . '.pdf');
    }
}
