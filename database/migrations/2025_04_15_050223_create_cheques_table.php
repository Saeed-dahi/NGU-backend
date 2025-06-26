<?php

use App\Enum\Cheque\ChequeDiscountType;
use App\Enum\Cheque\ChequeNature;
use App\Enum\Cheque\ChequeStatus;
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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cheque_number');
            $table->double('amount');
            $table->enum('nature', [ChequeNature::INCOMING->value, ChequeNature::OUTGOING->value]);
            $table->text('image')->nullable();
            $table->datetime('date');
            $table->datetime('due_date');
            $table->enum('status', [
                ChequeStatus::RECEIVED->value,
                ChequeStatus::DEPOSITED->value,
                ChequeStatus::BOUNCED->value,
                ChequeStatus::CANCELED->value
            ]);
            $table->text('notes')->nullable();
            $table->enum(
                'discount_type',
                [ChequeDiscountType::RECEIVED->value, ChequeDiscountType::ALLOWED->value]
            )->nullable();
            $table->double('discount_amount')->nullable();


            $table->unsignedBigInteger('issued_from_account_id');
            $table->unsignedBigInteger('issued_to_account_id');
            $table->unsignedBigInteger('target_bank_account_id');
            $table->unsignedBigInteger('discount_account_id');

            $table->foreign('issued_from_account_id')->references('id')->on('accounts');
            $table->foreign('issued_to_account_id')->references('id')->on('accounts');
            $table->foreign('target_bank_account_id')->references('id')->on('accounts');
            $table->foreign('discount_account_id')->references('id')->on('accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
