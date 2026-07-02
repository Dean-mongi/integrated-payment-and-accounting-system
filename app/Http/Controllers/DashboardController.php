<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BankDeposit;
use App\Models\FxRate;
use App\Models\LedgerEntry;
use App\Models\PaymentTransaction;
use App\Models\Reconciliation;
use App\Services\LedgerService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $transactions = PaymentTransaction::with('invoice.customer')->latest('processed_at')->limit(8)->get();
        $reconciliations = Reconciliation::with('bankDeposit')
            ->whereNotNull('bank_deposit_id')
            ->latest()
            ->limit(6)
            ->get();

        $summary = [
            'gross' => PaymentTransaction::sum('base_gross_amount'),
            'fees' => PaymentTransaction::sum('base_fee_amount'),
            'net' => PaymentTransaction::sum('base_net_amount'),
            'discrepancies' => Reconciliation::where('status', 'discrepancy')->count(),
            'unreconciled_deposits' => BankDeposit::doesntHave('reconciliation')->count(),
        ];

        $balances = $this->accountBalances();

        $daily = LedgerEntry::query()
            ->selectRaw('DATE(occurred_at) as day')
            ->selectRaw('SUM(base_debit) as debit')
            ->selectRaw('SUM(base_credit) as credit')
            ->groupBy(DB::raw('DATE(occurred_at)'))
            ->orderByDesc('day')
            ->limit(7)
            ->get();

        return view('dashboard', compact('transactions', 'reconciliations', 'summary', 'balances', 'daily'));
    }

    public function ledger()
    {
        $transactions = PaymentTransaction::with(['invoice.customer', 'ledgerEntries.account'])
            ->latest('processed_at')
            ->paginate(12);

        $balances = $this->accountBalances();

        $entries = LedgerEntry::with(['account', 'paymentTransaction'])
            ->latest('occurred_at')
            ->limit(20)
            ->get();

        return view('ledger', compact('transactions', 'balances', 'entries'));
    }

    public function reconciliation()
    {
        $reconciliations = Reconciliation::with('bankDeposit')
            ->whereNotNull('bank_deposit_id')
            ->latest()
            ->paginate(12);

        $deposits = BankDeposit::with('reconciliation')
            ->latest('deposited_at')
            ->limit(12)
            ->get();

        $summary = [
            'matched' => Reconciliation::where('status', 'matched')->count(),
            'discrepancies' => Reconciliation::where('status', 'discrepancy')->count(),
            'unreconciled_deposits' => BankDeposit::doesntHave('reconciliation')->count(),
        ];

        return view('reconciliation', compact('reconciliations', 'deposits', 'summary'));
    }

    public function fees()
    {
        $providerFees = PaymentTransaction::query()
            ->select('provider')
            ->selectRaw('COUNT(*) as transaction_count')
            ->selectRaw('SUM(gross_amount) as gross_amount')
            ->selectRaw('SUM(fee_amount) as fee_amount')
            ->selectRaw('SUM(net_amount) as net_amount')
            ->groupBy('provider')
            ->orderByDesc('fee_amount')
            ->get();

        $transactions = PaymentTransaction::with('invoice.customer')
            ->latest('processed_at')
            ->paginate(12);

        $summary = [
            'gross' => PaymentTransaction::sum('gross_amount'),
            'fees' => PaymentTransaction::sum('fee_amount'),
            'net' => PaymentTransaction::sum('net_amount'),
        ];

        return view('fees', compact('providerFees', 'transactions', 'summary'));
    }

    public function currency()
    {
        $rates = FxRate::latest('quoted_at')->limit(16)->get();

        $transactions = PaymentTransaction::query()
            ->where('currency', '<>', LedgerService::BASE_CURRENCY)
            ->latest('processed_at')
            ->paginate(12);

        $summary = [
            'base_currency' => LedgerService::BASE_CURRENCY,
            'foreign_volume' => PaymentTransaction::where('currency', '<>', LedgerService::BASE_CURRENCY)->sum('base_gross_amount'),
            'foreign_fees' => PaymentTransaction::where('currency', '<>', LedgerService::BASE_CURRENCY)->sum('base_fee_amount'),
            'rate_count' => FxRate::count(),
        ];

        return view('currency', compact('rates', 'transactions', 'summary'));
    }

    private function accountBalances()
    {
        return Account::query()
            ->leftJoin('ledger_entries', 'accounts.id', '=', 'ledger_entries.account_id')
            ->select('accounts.code', 'accounts.name', 'accounts.type')
            ->selectRaw('COALESCE(SUM(ledger_entries.base_debit - ledger_entries.base_credit), 0) as balance')
            ->groupBy('accounts.id', 'accounts.code', 'accounts.name', 'accounts.type')
            ->orderBy('accounts.code')
            ->get();
    }
}
