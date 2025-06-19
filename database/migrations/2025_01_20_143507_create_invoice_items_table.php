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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('product_unit_id');
            $table->text('description')->nullable();
            $table->double('quantity');
            $table->double('price');
            $table->double('tax_amount')->default(0);
            $table->double('discount_amount')->default(0);
            $table->double('total');
            $table->double('product_unit_new_quantity')->default(0);
            $table->datetime('date');
            $table->softDeletes();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('product_unit_id')->references('id')->on('product_units');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
