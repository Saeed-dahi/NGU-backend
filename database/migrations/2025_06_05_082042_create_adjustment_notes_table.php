<?php

use App\Enum\Account\AccountNature;
use App\Enum\Status;
use App\Models\Account\Account;
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
        Schema::create('adjustment_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('number')->unique();
            $table->string('document_number')->nullable();
            $table->unique(['type', 'document_number']);
            $table->enum('type', [AccountNature::DEBIT->value, AccountNature::CREDIT->value]);
            $table->enum('status', [Status::DRAFT->value, Status::SAVED->value]);
            $table->dateTime('date');

            $table->string('description')->nullable();
            $table->double('sub_total');
            $table->double('total');

            $table->unsignedBigInteger('primary_account_id');
            $table->unsignedBigInteger('secondary_account_id');

            $table->unsignedBigInteger('tax_account_id');
            $table->double('tax_amount')->default(0);

            $table->unsignedBigInteger('cheque_id')->nullable();

            $table->foreign('primary_account_id')->references('id')->on('accounts');
            $table->foreign('secondary_account_id')->references('id')->on('accounts');
            $table->foreign('tax_account_id')->references('id')->on('accounts');
            $table->foreign('cheque_id')->references('id')->on('cheques');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment_notes');
    }
};
