<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role', 30)->default('customer')->after('email');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('invoices', 'paid_amount')) {
                $table->decimal('paid_amount', 15, 2)->default(0)->after('total');
            }
            if (! Schema::hasColumn('invoices', 'notes')) {
                $table->text('notes')->nullable()->after('paid_at');
            }
        });

        if (! Schema::hasTable('invoice_items')) {
            Schema::create('invoice_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
                $table->string('description');
                $table->decimal('quantity', 12, 2)->default(1);
                $table->decimal('unit_price', 15, 2);
                $table->decimal('line_total', 15, 2);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('receipts')) {
            Schema::create('receipts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
                $table->foreignId('payment_transaction_id')->constrained()->cascadeOnDelete();
                $table->string('receipt_no')->unique();
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3);
                $table->string('payment_method', 40);
                $table->timestamp('issued_at');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('system_notifications')) {
            Schema::create('system_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('type', 60);
                $table->string('title');
                $table->text('message');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['type', 'read_at']);
            });
        }

        if (! Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('event');
                $table->string('auditable_type')->nullable();
                $table->unsignedBigInteger('auditable_id')->nullable();
                $table->json('metadata')->nullable();
                $table->ipAddress('ip_address')->nullable();
                $table->timestamps();

                $table->index(['auditable_type', 'auditable_id']);
                $table->index('event');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('system_notifications');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('invoice_items');

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('invoices', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
