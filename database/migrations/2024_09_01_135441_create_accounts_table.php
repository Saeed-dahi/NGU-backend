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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('en_name')->unique();
            $table->string('ar_name')->unique();
            $table->enum('account_type', ['main', 'sub']);
            $table->enum('account_nature', ['debit', 'credit'])->nullable();
            $table->enum('account_category', ['asset', 'liability', 'equity', 'revenue', 'expense'])->nullable();
            $table->decimal('balance', 10, 2)->default(0);
            $table->unsignedBigInteger('closing_account_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            // foreign
            $table->foreign('closing_account_id')->references('id')->on('closing_accounts');
            $table->foreign('parent_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
