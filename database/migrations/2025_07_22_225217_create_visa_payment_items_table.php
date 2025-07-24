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
        Schema::create('visa_payment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visa_payment_id');
            $table->unsignedBigInteger('customer_account_id');
            $table->double('amount');
            $table->text('notes')->nullable();

            $table->foreign('visa_payment_id')->references('id')->on('visa_payments');
            $table->foreign('customer_account_id')->references('id')->on('accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_payment_items');
    }
};
