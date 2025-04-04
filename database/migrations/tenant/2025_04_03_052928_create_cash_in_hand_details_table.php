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
        Schema::create('cash_in_hand_details', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->useCurrent();
            $table->decimal('amount', 10, 2);
            $table->string('transaction_type', 50);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable(); // Polymorphic relationship
            $table->timestamps();
            $table->softDeletes();

            $table->index('date');
            $table->index('transaction_type');
            $table->index(['reference_id', 'reference_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_in_hand_details');
    }
};
