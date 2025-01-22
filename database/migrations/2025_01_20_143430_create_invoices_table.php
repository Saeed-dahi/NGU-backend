<?php

use App\Enum\Account\AccountNature;
use App\Enum\Invoice\InvoiceType;
use App\Enum\Status;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_number')->unique();
            $table->enum('type', [
                InvoiceType::PURCHASE->value,
                InvoiceType::SALES->value,
            ]);
            $table->datetime('date');
            $table->datetime('due_date')->nullable();
            $table->enum('status', [Status::DRAFT->value, Status::SAVED->value]);
            $table->enum('invoice_nature', [AccountNature::CREDIT->value, AccountNature::DEBIT->value]);
            $table->string('currency')->default('AED');
            $table->double('sub_total')->default(0);
            $table->double('total')->default(0);
            $table->text('notes')->nullable();
            $table->softDeletes();

            $table->unsignedBigInteger('account_id'); // Customer, Supplier
            $table->unsignedBigInteger('total_tax_account');
            $table->double('total_tax');
            $table->unsignedBigInteger('total_discount_account');
            $table->double('total_discount');

            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('total_tax_account')->references('id')->on('accounts');
            $table->foreign('total_discount_account')->references('id')->on('accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
