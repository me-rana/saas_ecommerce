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
            $table->string('invoice_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('shipping_id')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('status')->nullable()->default(1);
            $table->string('shipping_charge')->nullable()->default(0);
            $table->string('note')->nullable();
            $table->string('order_note')->nullable();
            $table->string('ip_address')->nullable();
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
