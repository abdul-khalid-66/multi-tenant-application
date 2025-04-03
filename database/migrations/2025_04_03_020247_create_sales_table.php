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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->string('invoice_no', 100)->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('cost_price', 10, 2);
            $table->timestamp('date')->useCurrent();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('payment_status', 20)->default('pending');
            $table->string('payment_method', 50);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_no');
            $table->index('date');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
