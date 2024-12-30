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
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('base_product_unit_id')->nullable();
            $table->decimal('conversion_factor', 10, 2)->nullable();

            $table->decimal('export_price', 10, 2)->default(0);
            $table->decimal('import_price', 10, 2)->default(0);
            $table->decimal('wholesale_price', 10, 2)->default(0);
            $table->decimal('end_price', 10, 2)->default(0);
            $table->decimal('quantity', 10, 2)->default(0);

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('base_product_unit_id')->references('id')->on('product_units');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};
