<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        collect([
            ['1010', 'Processor clearing', 'asset'],
            ['1020', 'Bank cash', 'asset'],
            ['4010', 'Sales revenue', 'revenue'],
            ['4020', 'Subscription revenue', 'revenue'],
            ['6100', 'Processor fees', 'expense'],
            ['6200', 'Outbound payouts', 'expense'],
            ['7100', 'Realized FX gain/loss', 'income_statement'],
        ])->each(fn (array $account) => Account::updateOrCreate(
            ['code' => $account[0]],
            ['name' => $account[1], 'type' => $account[2], 'currency' => 'USD']
        ));

        collect([
            ['Admin User', 'admin@example.com', 'admin'],
            ['Accountant User', 'accountant@example.com', 'accountant'],
            ['Cashier User', 'cashier@example.com', 'cashier'],
            ['Customer User', 'customer@example.com', 'customer'],
        ])->each(fn (array $user) => User::updateOrCreate(
            ['email' => $user[1]],
            ['name' => $user[0], 'role' => $user[2], 'password' => Hash::make('password')]
        ));

        Business::updateOrCreate(
            ['name' => 'MaliHub'],
            [
                'tagline' => 'Your Financial Hub. Grow Better.',
                'email' => 'finance@malihub.local',
                'phone' => '+254 700 000 000',
                'tax_number' => 'PIN-MALIHUB',
                'address' => 'Nairobi, Kenya',
                'base_currency' => 'USD',
            ]
        );

        collect([
            ['Acme Stores', 'accounts@acme.test', 'USD', 'Kenya'],
            ['Savannah Traders', 'finance@savannah.test', 'KES', 'Kenya'],
            ['Blue Ridge Services', 'billing@blueridge.test', 'USD', 'Uganda'],
        ])->each(fn (array $customer) => Customer::updateOrCreate(
            ['email' => $customer[1]],
            ['name' => $customer[0], 'default_currency' => $customer[2], 'country' => $customer[3]]
        ));

        collect([
            ['Nairobi Office Supplies', 'orders@office.test', '+254 711 000 111', 'Office', 320.00],
            ['Cloud Hosting Ltd', 'billing@cloud.test', '+254 722 000 222', 'Technology', 120.00],
            ['Courier Express', 'dispatch@courier.test', '+254 733 000 333', 'Logistics', 85.00],
        ])->each(fn (array $supplier) => Supplier::updateOrCreate(
            ['email' => $supplier[1]],
            ['name' => $supplier[0], 'phone' => $supplier[2], 'category' => $supplier[3], 'balance' => $supplier[4], 'status' => 'active']
        ));

        collect([
            ['MH-SVC-001', 'Accounting setup package', 'Services', 450.00, 15],
            ['MH-SVC-002', 'Monthly bookkeeping', 'Services', 180.00, 40],
            ['MH-POS-001', 'Receipt printer integration', 'Hardware', 95.00, 8],
        ])->each(fn (array $product) => Product::updateOrCreate(
            ['sku' => $product[0]],
            ['name' => $product[1], 'category' => $product[2], 'unit_price' => $product[3], 'stock_quantity' => $product[4], 'status' => 'active']
        ));

        $supplier = Supplier::first();
        collect([
            ['Operations', 'Office internet and utilities', 240.00, 'bank_transfer', now()->subDays(5)->toDateString()],
            ['Marketing', 'Customer acquisition campaign', 380.00, 'mobile_money', now()->subDays(11)->toDateString()],
            ['Office', 'Stationery and printer paper', 76.50, 'cash', now()->subDays(18)->toDateString()],
        ])->each(fn (array $expense) => Expense::updateOrCreate(
            ['description' => $expense[1], 'spent_at' => $expense[4]],
            ['supplier_id' => $supplier?->id, 'category' => $expense[0], 'amount' => $expense[2], 'currency' => 'USD', 'payment_method' => $expense[3]]
        ));
    }
}
