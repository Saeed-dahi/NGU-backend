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
        Schema::create('adjustment_note_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adjustment_note_id');
            $table->unsignedBigInteger('product_units_id');
            $table->integer('quantity');
            $table->double('price');
            $table->double('tax_amount')->default(0);
            $table->double('discount_amount')->default(0);
            $table->double('total');
            $table->timestamps();


            $table->foreign('adjustment_note_id')->references('id')->on('adjustment_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment_note_items');
    }
};
