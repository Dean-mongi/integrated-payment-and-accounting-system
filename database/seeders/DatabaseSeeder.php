<?php

namespace Database\Seeders;

use App\Models\Account;
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
    }
}
