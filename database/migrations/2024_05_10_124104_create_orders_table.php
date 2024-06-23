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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cashier_id');
            $table->integer('total_quantity')->default(0);
            $table->decimal('total_price', 8, 2)->default(0);
            $table->string('transaction_id')->nullable(); // Moved transaction_id column here
            // Foreign key constraint
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
            
            // Timestamps should be placed last
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
