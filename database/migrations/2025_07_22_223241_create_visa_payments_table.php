<?php

use App\Enum\VisaPayment\VisaPaymentStatus;
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
        Schema::create('visa_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('document_number');
            $table->double('gross_amount');
            $table->double('commission_rate');
            $table->double('commission_amount');
            $table->double('tax_amount');
            $table->double('net_amount');
            $table->enum('status', [
                VisaPaymentStatus::RECEIVED->value,
                VisaPaymentStatus::DEPOSITED->value,
                VisaPaymentStatus::BOUNCED->value,
                VisaPaymentStatus::CANCELED->value
            ]);

            $table->dateTime('date');
            $table->dateTime('due_date');
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('bank_account_id');
            $table->unsignedBigInteger('machine_account_id');
            $table->unsignedBigInteger('commission_account_id');
            $table->unsignedBigInteger('tax_account_id')->nullable();

            $table->foreign('bank_account_id')->references('id')->on('accounts');
            $table->foreign('machine_account_id')->references('id')->on('accounts');
            $table->foreign('commission_account_id')->references('id')->on('accounts');
            $table->foreign('tax_account_id')->references('id')->on('accounts');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_payments');
    }
};
