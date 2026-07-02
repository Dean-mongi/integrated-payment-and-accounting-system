<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BankDeposit;
use App\Models\Reconciliation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


final class ReconciliationService
{
    /**
     * Create a bank deposit and compare it with the posted processor net total.
     *
     * @param array<string, mixed> $data
     */
    public function createAndMatch(array $data): Reconciliation
    {
        $threshold = (float) config('accounting.reconciliation_threshold', 0.01);

        return DB::transaction(function () use ($data, $threshold): Reconciliation {
            $currency = strtoupper((string) $data['currency']);
            $rate = (float) $data['exchange_rate'];
            $amount = (float) $data['amount'];
            $baseAmount = round($amount * $rate, 2);

            $deposit = BankDeposit::create([
                'processor' => $data['processor'],
                'deposit_reference' => $data['deposit_reference'],
                'amount' => $amount,
                'currency' => $currency,
                'exchange_rate' => $rate,
                'base_amount' => $baseAmount,
                'deposited_at' => $data['deposited_at'],
            ]);

            $expected = (float) DB::table('payment_transactions')
                ->where('provider', $data['processor'])
                ->where('currency', $currency)
                ->where('status', 'posted')
                ->sum('net_amount');

            $difference = round($amount - $expected, 2);

            return Reconciliation::create([
                'bank_deposit_id' => $deposit->id,
                'status' => abs($difference) <= $threshold ? 'matched' : 'discrepancy',
                'expected_net_amount' => $expected,
                'difference' => $difference,
                'notes' => abs($difference) <= $threshold
                    ? 'Deposit matched posted processor net total.'
                    : 'Deposit amount differs from posted processor net total.',
            ]);
        });
    }

    /**
     * Run a daily reconciliation report.
     *
     * NOTE: The current codebase uses legacy model names (Reconciliation, etc.).
     * This service computes totals from the spec-aligned tables via the query builder,
     * then stores into the existing `reconciliations` table/structure.
     *
     * @param Carbon $date
     * @param float|null $threshold
     */
    public function runDailyReconciliation(Carbon $date, ?float $threshold = null): Reconciliation
    {
        $threshold = $threshold ?? (float) config('accounting.reconciliation_threshold', 0.01);

        return DB::transaction(function () use ($date, $threshold): Reconciliation {
            $bankTotal = (float) DB::table('bank_statements')
                ->whereDate('statement_date', $date->toDateString())
                ->where('status', 'unmatched')
                ->sum('amount');

            $processorTotal = (float) DB::table('processor_statements')
                ->whereDate('period_end', $date->toDateString())
                ->sum('net_payout');

            $invoiceTotal = (float) DB::table('invoice_payments')
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount_paid');

            $discrepancy = round($bankTotal - $processorTotal, 2);

            $status = abs($discrepancy) > $threshold ? 'discrepancy' : 'matched';

            $flaggedItems = [];
            if (abs($discrepancy) > $threshold) {
                $flaggedItems[] = [
                    'bank_total' => $bankTotal,
                    'processor_total' => $processorTotal,
                    'invoice_total' => $invoiceTotal,
                    'discrepancy' => $discrepancy,
                    'threshold' => $threshold,
                ];
            }

            $reconciliation = Reconciliation::create([
                'bank_deposit_id' => null,
                'date' => $date->toDateString(),
                'bank_total' => $bankTotal,
                'processor_total' => $processorTotal,
                'invoice_total' => $invoiceTotal,
                'discrepancy_amount' => $discrepancy,
                'status' => $status,
                'flagged_items' => $flaggedItems,
            ]);


            return $reconciliation;
        });
    }
}
