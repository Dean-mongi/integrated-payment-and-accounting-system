<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
                $table->string('provider', 50);
                $table->string('provider_reference', 100)->unique();
                $table->string('type', 30);
                $table->decimal('gross_amount', 15, 2);
                $table->decimal('fee_amount', 15, 2)->default(0);
                $table->decimal('net_amount', 15, 2);
                $table->string('currency', 3);
                $table->string('base_currency', 3)->default('USD');
                $table->decimal('exchange_rate', 18, 6)->default(1);
                $table->decimal('base_gross_amount', 15, 2);
                $table->decimal('base_fee_amount', 15, 2)->default(0);
                $table->decimal('base_net_amount', 15, 2);
                $table->string('status')->default('posted');
                $table->timestamp('processed_at');
                $table->timestamps();

                $table->index(['provider', 'currency', 'status']);
                $table->index('processed_at');
            });
        }

        if (! Schema::hasTable('ledger_entries')) {
            Schema::create('ledger_entries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_transaction_id')->constrained()->cascadeOnDelete();
                $table->foreignId('account_id')->constrained()->restrictOnDelete();
                $table->string('description')->nullable();
                $table->decimal('debit', 15, 2)->default(0);
                $table->decimal('credit', 15, 2)->default(0);
                $table->string('currency', 3)->default('USD');
                $table->decimal('exchange_rate', 18, 6)->default(1);
                $table->decimal('base_debit', 15, 2)->default(0);
                $table->decimal('base_credit', 15, 2)->default(0);
                $table->timestamp('occurred_at');
                $table->timestamps();

                $table->index(['occurred_at', 'account_id']);
                $table->index('payment_transaction_id');
            });
        }

        if (! Schema::hasTable('bank_deposits')) {
            Schema::create('bank_deposits', function (Blueprint $table) {
                $table->id();
                $table->string('processor', 50);
                $table->string('deposit_reference', 100)->unique();
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3);
                $table->decimal('exchange_rate', 18, 6)->default(1);
                $table->decimal('base_amount', 15, 2);
                $table->date('deposited_at');
                $table->timestamps();

                $table->index(['processor', 'currency', 'deposited_at']);
            });
        }

        if (! Schema::hasTable('reconciliations')) {
            Schema::create('reconciliations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bank_deposit_id')->nullable()->constrained()->nullOnDelete();
                $table->date('date')->nullable();
                $table->decimal('bank_total', 15, 2)->default(0);
                $table->decimal('processor_total', 15, 2)->default(0);
                $table->decimal('invoice_total', 15, 2)->default(0);
                $table->decimal('discrepancy_amount', 15, 2)->default(0);
                $table->decimal('expected_net_amount', 15, 2)->default(0);
                $table->decimal('difference', 15, 2)->default(0);
                $table->string('status');
                $table->json('flagged_items')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['date', 'status']);
                $table->index('bank_deposit_id');
            });
        }

        if (! Schema::hasTable('fx_rates')) {
            Schema::create('fx_rates', function (Blueprint $table) {
                $table->id();
                $table->string('from_currency', 3);
                $table->string('to_currency', 3);
                $table->decimal('rate', 18, 6);
                $table->timestamp('quoted_at');
                $table->timestamps();

                $table->unique(['from_currency', 'to_currency', 'rate', 'quoted_at'], 'fx_rate_quote_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fx_rates');
        Schema::dropIfExists('reconciliations');
        Schema::dropIfExists('bank_deposits');
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('payment_transactions');
    }
};
