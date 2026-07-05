<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('businesses')) {
            Schema::create('businesses', function (Blueprint $table) {
                $table->id();
                $table->string('name')->default('MaliHub');
                $table->string('tagline')->default('Your Financial Hub. Grow Better.');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('tax_number')->nullable();
                $table->text('address')->nullable();
                $table->string('base_currency', 3)->default('USD');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('category')->nullable();
                $table->decimal('balance', 15, 2)->default(0);
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('sku')->unique();
                $table->string('name');
                $table->string('category')->nullable();
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->unsignedInteger('stock_quantity')->default(0);
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
                $table->string('category');
                $table->string('description');
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3)->default('USD');
                $table->string('payment_method', 40)->default('cash');
                $table->string('receipt_path')->nullable();
                $table->date('spent_at');
                $table->timestamps();

                $table->index(['category', 'spent_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('businesses');
    }
};
