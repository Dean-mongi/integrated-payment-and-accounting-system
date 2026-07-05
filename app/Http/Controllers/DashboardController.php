<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BankDeposit;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\FxRate;
use App\Models\Invoice;
use App\Models\LedgerEntry;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\Reconciliation;
use App\Models\AuditLog;
use App\Models\Supplier;
use App\Models\SystemNotification;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'expenses' => Expense::sum('amount'),
            'outstanding_invoices' => Invoice::whereIn('status', ['unpaid', 'partially_paid', 'overdue'])->sum(DB::raw('total - paid_amount')),
            'customers' => Customer::count(),
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

    public function customers()
    {
        $customers = Customer::withCount('invoices')
            ->withSum('invoices as invoice_total', 'total')
            ->withSum('invoices as paid_total', 'paid_amount')
            ->orderBy('name')
            ->paginate(12);

        $summary = [
            'customers' => Customer::count(),
            'invoice_total' => Invoice::sum('total'),
            'paid_total' => Invoice::sum('paid_amount'),
            'outstanding' => Invoice::sum(DB::raw('total - paid_amount')),
        ];

        return view('customers.index', compact('customers', 'summary'));
    }

    public function suppliers()
    {
        $suppliers = Supplier::orderBy('name')->paginate(12);

        $summary = [
            'suppliers' => Supplier::count(),
            'active' => Supplier::where('status', 'active')->count(),
            'balance' => Supplier::sum('balance'),
            'expense_total' => Expense::sum('amount'),
        ];

        return view('suppliers.index', compact('suppliers', 'summary'));
    }

    public function products()
    {
        $products = Product::orderBy('name')->paginate(12);

        $summary = [
            'products' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'stock' => Product::sum('stock_quantity'),
            'inventory_value' => Product::selectRaw('SUM(unit_price * stock_quantity) as value')->value('value') ?? 0,
        ];

        return view('products.index', compact('products', 'summary'));
    }

    public function expenses()
    {
        $expenses = Expense::with('supplier')->latest('spent_at')->paginate(12);

        $byCategory = Expense::query()
            ->select('category')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $summary = [
            'total' => Expense::sum('amount'),
            'monthly' => Expense::where('spent_at', '>=', now()->startOfMonth())->sum('amount'),
            'receipts' => Expense::whereNotNull('receipt_path')->count(),
            'categories' => Expense::distinct('category')->count('category'),
        ];

        return view('expenses.index', compact('expenses', 'byCategory', 'summary'));
    }

    public function accounting()
    {
        $balances = $this->accountBalances();
        $revenue = PaymentTransaction::sum('base_gross_amount');
        $paymentFees = PaymentTransaction::sum('base_fee_amount');
        $operatingExpenses = Expense::sum('amount');
        $expenses = $paymentFees + $operatingExpenses;

        $statements = [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'profit' => $revenue - $expenses,
            'assets' => (float) $balances->where('type', 'asset')->sum('balance'),
            'liabilities' => abs((float) $balances->where('type', 'liability')->sum('balance')),
            'equity' => (float) $balances->whereIn('type', ['equity', 'income_statement', 'revenue'])->sum('balance'),
            'cash_in' => PaymentTransaction::sum('base_net_amount'),
            'cash_out' => $operatingExpenses,
        ];

        return view('accounting.index', compact('balances', 'statements'));
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

    public function analytics()
    {
        $transactions = PaymentTransaction::with('invoice.customer')
            ->latest('processed_at')
            ->get();

        $monthly = $transactions
            ->groupBy(fn (PaymentTransaction $transaction): string => $transaction->processed_at->format('M Y'))
            ->map(fn ($items, string $month): array => [
                'month' => $month,
                'gross' => (float) $items->sum('base_gross_amount'),
                'fees' => (float) $items->sum('base_fee_amount'),
                'net' => (float) $items->sum('base_net_amount'),
            ])
            ->values()
            ->take(12);

        $providerPerformance = $transactions
            ->groupBy('provider')
            ->map(fn ($items, string $provider): array => [
                'provider' => $provider,
                'count' => $items->count(),
                'gross' => (float) $items->sum('base_gross_amount'),
                'fees' => (float) $items->sum('base_fee_amount'),
                'net' => (float) $items->sum('base_net_amount'),
                'fee_rate' => (float) $items->sum('base_gross_amount') > 0
                    ? ((float) $items->sum('base_fee_amount') / (float) $items->sum('base_gross_amount')) * 100
                    : 0,
            ])
            ->sortByDesc('net')
            ->values();

        $typeMix = $transactions
            ->groupBy('type')
            ->map(fn ($items, string $type): array => [
                'type' => $type,
                'count' => $items->count(),
                'net' => (float) $items->sum('base_net_amount'),
            ])
            ->values();

        $summary = [
            'transactions' => $transactions->count(),
            'gross' => (float) $transactions->sum('base_gross_amount'),
            'fees' => (float) $transactions->sum('base_fee_amount'),
            'net' => (float) $transactions->sum('base_net_amount'),
        ];

        return view('analytics', compact('monthly', 'providerPerformance', 'typeMix', 'summary'));
    }

    public function reports()
    {
        $balances = $this->accountBalances();

        $transactions = PaymentTransaction::with('invoice.customer')
            ->latest('processed_at')
            ->limit(20)
            ->get();

        $reconciliations = Reconciliation::with('bankDeposit')
            ->latest()
            ->limit(20)
            ->get();

        $reportCards = [
            'ledger_entries' => LedgerEntry::count(),
            'transactions' => PaymentTransaction::count(),
            'sales' => Invoice::sum('total'),
            'purchases' => Expense::sum('amount'),
            'vat_tax' => Invoice::sum('tax'),
            'matched_reconciliations' => Reconciliation::where('status', 'matched')->count(),
            'discrepancies' => Reconciliation::where('status', 'discrepancy')->count(),
        ];

        return view('reports', compact('balances', 'transactions', 'reconciliations', 'reportCards'));
    }

    public function settings()
    {
        $business = Business::first();
        $accounts = Account::orderBy('code')->get();

        $processors = PaymentTransaction::query()
            ->select('provider')
            ->selectRaw('COUNT(*) as transaction_count')
            ->groupBy('provider')
            ->orderBy('provider')
            ->get();

        $settings = [
            'app_name' => config('app.name'),
            'environment' => config('app.env'),
            'base_currency' => LedgerService::BASE_CURRENCY,
            'reconciliation_threshold' => (float) config('accounting.reconciliation_threshold', 0.01),
            'mail_driver' => config('mail.default'),
            'queue_connection' => config('queue.default'),
        ];

        $notifications = SystemNotification::latest()->limit(10)->get();
        $auditLogs = AuditLog::with('user')->latest()->limit(10)->get();

        return view('settings', compact('business', 'accounts', 'processors', 'settings', 'notifications', 'auditLogs'));
    }

    public function backup(Request $request)
    {
        $payload = [
            'generated_at' => now()->toIso8601String(),
            'accounts' => Account::orderBy('code')->get(),
            'invoices' => \App\Models\Invoice::with(['customer', 'items', 'receipts'])->get(),
            'transactions' => PaymentTransaction::with('ledgerEntries')->get(),
            'reconciliations' => Reconciliation::with('bankDeposit')->get(),
        ];

        $path = 'backups/finance-backup-'.now()->format('Ymd-His').'.json';
        Storage::disk('local')->put($path, json_encode($payload, JSON_PRETTY_PRINT));

        SystemNotification::create([
            'type' => 'backup_completed',
            'title' => 'Backup completed',
            'message' => 'Finance backup saved to storage/app/'.$path.'.',
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'event' => 'backup.created',
            'metadata' => ['path' => $path],
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('settings')->with('status', 'Backup created at storage/app/'.$path.'.');
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
