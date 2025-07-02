<?php

use App\Enum\Account\AccountNature;
use App\Enum\Invoice\DiscountType;
use App\Enum\Invoice\InvoiceCommissionType;
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
            $table->bigInteger('invoice_number');
            $table->string('document_number')->nullable();
            $table->unique(['type', 'invoice_number']);
            $table->enum('type', [
                InvoiceType::PURCHASE->value,
                InvoiceType::SALES->value,
                InvoiceType::SALES_Return->value,
                InvoiceType::PURCHASE_RETURN->value
            ]);
            $table->datetime('date');
            $table->datetime('due_date')->nullable();
            $table->enum('status', [Status::DRAFT->value, Status::SAVED->value]);
            $table->enum('invoice_nature', [AccountNature::CREDIT->value, AccountNature::DEBIT->value])->nullable();
            $table->text('address')->nullable();
            $table->string('currency')->default('AED');
            $table->double('sub_total')->default(0);
            $table->double('total')->default(0);
            $table->text('notes')->nullable();
            $table->softDeletes();


            $table->unsignedBigInteger('goods_account_id');
            $table->unsignedBigInteger('account_id'); // Customer, Supplier
            $table->unsignedBigInteger('tax_account_id');
            $table->double('tax_amount')->default(0);

            $table->unsignedBigInteger('discount_account_id');
            $table->double('discount_amount')->default(0);
            $table->enum('discount_type', [DiscountType::PERCENTAGE->value, DiscountType::AMOUNT->value])->nullable();

            $table->foreign('goods_account_id')->references('id')->on('accounts');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('tax_account_id')->references('id')->on('accounts');
            $table->foreign('discount_account_id')->references('id')->on('accounts');
            $table->timestamps();


            // Commission
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('commission_account_id')->nullable();
            $table->enum('commission_type', [InvoiceCommissionType::TOTAL->value, InvoiceCommissionType::PROFIT->value])->nullable();
            $table->double('commission_rate')->nullable();
            $table->double('commission_amount')->nullable();


            $table->foreign('agent_id')->references('id')->on('accounts');
            $table->foreign('commission_account_id')->references('id')->on('accounts');
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
