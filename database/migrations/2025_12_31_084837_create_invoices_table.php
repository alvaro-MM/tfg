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
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('table_id')->constrained('tables')->cascadeOnDelete();
            $table->decimal('total', 10, 2);
            $table->dateTime('date');
            
            // Customer information
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_notes')->nullable();
            
            // Payment information
            $table->string('payment_method')->nullable(); // cash, card, mobile, transfer, cash
            $table->string('payment_status')->nullable(); // pending, paid, failed
            $table->string('payment_reference')->nullable(); // reference number from the payment gateway
            $table->string('payment_amount')->nullable();
            $table->string('payment_currency')->nullable(); // EUR
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
