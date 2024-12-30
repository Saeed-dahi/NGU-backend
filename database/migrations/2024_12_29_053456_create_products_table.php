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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('ar_name');
            $table->string('en_name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('barcode')->nullable();
            $table->text('file')->nullable();
            $table->enum('type', [
                'commercial',
                'finished',
                'raw',
                'assembly',
                'running',
                'semi_finished',
                'spare_parts',
                'production_requirements',
                'service'
            ])->nullable();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
