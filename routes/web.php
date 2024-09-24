<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'generatePDF'])->name('invoices.pdf');
Route::get('/', function () {
    return view('welcome');
});
