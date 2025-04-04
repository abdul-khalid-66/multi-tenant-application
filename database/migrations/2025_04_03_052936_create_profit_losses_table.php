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
        Schema::create('profit_losses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('profit', 10, 2)->default(0);
            $table->decimal('loss', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('category', 50);
            $table->string('verified_by', 100)->nullable();
            $table->timestamp('date')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index('date');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_losses');
    }
};
