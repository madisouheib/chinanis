<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->date('from'); // Date of invoice creation or start
            $table->date('to'); // Estimated delivery date
            $table->decimal('total_amount', 10, 2); // Total invoice amount
            $table->decimal('paid_amount', 10, 2)->default(0); // Amount paid
            $table->enum('status', ['draft', 'approved', 'in_process', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
