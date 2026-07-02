<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
    }
}
