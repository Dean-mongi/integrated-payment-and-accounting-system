<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('code')->unique();
            $table->string('currency', 3)->default('USD');
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('default_currency', 3)->default('USD');
            $table->string('country')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_no')->unique();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('draft');
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('type'); // inbound/outbound/transfer
            $table->decimal('gross_amount', 15, 2);
            $table->decimal('net_amount', 15, 2);
            $table->decimal('fee_amount', 15, 2)->default(0);
            $table->string('currency', 3);
            $table->decimal('exchange_rate', 18, 6)->default(1);
            $table->string('base_currency', 3)->default('USD');
            $table->decimal('base_currency_amount', 15, 2)->default(0);
            $table->decimal('fx_gain_loss', 15, 2)->default(0);
            $table->string('status')->default('posted');
            $table->string('source'); // stripe/paypal/manual
            $table->string('payment_intent_id')->nullable()->unique();
            $table->timestamp('locked_exchange_rate_at')->nullable();
            $table->timestamps();
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('account_id')->constrained()->restrictOnDelete();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('description')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['occurred_at', 'account_id']);
            $table->index(['transaction_id']);
        });

        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_paid', 15, 2);
            $table->timestamps();

            $table->unique(['invoice_id', 'transaction_id'], 'invoice_payment_unique');
        });

        Schema::create('bank_statements', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->date('statement_date');
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->foreignId('matched_transaction_id')->nullable()->constrained('transactions')->nullOnDelete();

            $table->index(['matched_transaction_id']);

            $table->string('status')->default('unmatched'); // unmatched/matched/flagged
            $table->timestamps();

            $table->index(['statement_date', 'bank_name', 'status']);
        });

        Schema::create('processor_statements', function (Blueprint $table) {
            $table->id();
            $table->string('processor'); // stripe/paypal
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('gross_volume', 15, 2)->default(0);
            $table->decimal('fees', 15, 2)->default(0);
            $table->decimal('net_payout', 15, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->longText('raw_payload');
            $table->timestamps();

            $table->index(['processor', 'period_end']);
        });

        Schema::create('reconciliation_reports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('bank_total', 15, 2)->default(0);
            $table->decimal('processor_total', 15, 2)->default(0);
            $table->decimal('invoice_total', 15, 2)->default(0);
            $table->decimal('discrepancy_amount', 15, 2)->default(0);
            $table->string('status'); // balanced/discrepancy
            $table->longText('flagged_items')->nullable();
            $table->timestamps();

            $table->unique(['date', 'status'], 'reconciliation_date_status_unique');
        });

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 3);
            $table->string('to_currency', 3);
            $table->decimal('rate', 18, 6);
            $table->string('source');
            $table->timestamp('fetched_at');
            $table->timestamps();

            $table->index(['from_currency', 'to_currency', 'fetched_at']);
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('plan');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('interval');
            $table->date('next_billing_date')->nullable();
            $table->string('status')->default('active');
            $table->string('processor_subscription_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('exchange_rates');
        Schema::dropIfExists('reconciliation_reports');
        Schema::dropIfExists('processor_statements');
        Schema::dropIfExists('bank_statements');
        Schema::dropIfExists('invoice_payments');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('accounts');
    }
};

