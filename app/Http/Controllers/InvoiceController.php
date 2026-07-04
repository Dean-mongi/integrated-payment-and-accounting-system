<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('customer')->latest();

        if ($request->user()->role === 'customer') {
            $query->whereHas('customer', fn ($customer) => $customer->where('email', $request->user()->email));
        }

        $invoices = $query->paginate(12);

        $summary = [
            'paid' => Invoice::where('status', 'paid')->count(),
            'unpaid' => Invoice::where('status', 'unpaid')->count(),
            'partial' => Invoice::where('status', 'partially_paid')->count(),
            'overdue' => Invoice::where('status', 'overdue')->count(),
        ];

        return view('invoices.index', compact('invoices', 'summary'));
    }

    public function create(Request $request)
    {
        abort_if(! in_array($request->user()->role, ['admin', 'cashier'], true), 403);

        return view('invoices.create');
    }

    public function store(Request $request)
    {
        abort_if(! in_array($request->user()->role, ['admin', 'cashier'], true), 403);

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:120'],
            'currency' => ['required', 'string', 'size:3'],
            'due_date' => ['required', 'date'],
            'description' => ['required', 'array', 'min:1'],
            'description.*' => ['required', 'string', 'max:180'],
            'quantity' => ['required', 'array', 'min:1'],
            'quantity.*' => ['required', 'numeric', 'min:0.01'],
            'unit_price' => ['required', 'array', 'min:1'],
            'unit_price.*' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $invoice = DB::transaction(function () use ($request, $data): Invoice {
            $customer = Customer::updateOrCreate(
                ['email' => $data['customer_email']],
                ['name' => $data['customer_name']]
            );

            $items = collect($data['description'])->map(function (string $description, int $index) use ($data): array {
                $quantity = (float) $data['quantity'][$index];
                $unitPrice = (float) $data['unit_price'][$index];

                return [
                    'description' => $description,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => round($quantity * $unitPrice, 2),
                ];
            });

            $subtotal = round((float) $items->sum('line_total'), 2);

            $invoice = Invoice::create([
                'customer_id' => $customer->id,
                'invoice_no' => 'INV-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                'subtotal' => $subtotal,
                'tax' => 0,
                'total' => $subtotal,
                'paid_amount' => 0,
                'currency' => strtoupper($data['currency']),
                'status' => now()->toDateString() > $data['due_date'] ? 'overdue' : 'unpaid',
                'due_date' => $data['due_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $invoice->items()->createMany($items->all());

            SystemNotification::create([
                'type' => 'invoice_created',
                'title' => 'Invoice created',
                'message' => 'Invoice '.$invoice->invoice_no.' was created for '.$customer->name.'.',
            ]);

            AuditLog::create([
                'user_id' => $request->user()->id,
                'event' => 'invoice.created',
                'auditable_type' => Invoice::class,
                'auditable_id' => $invoice->id,
                'metadata' => ['total' => $invoice->total, 'currency' => $invoice->currency],
                'ip_address' => $request->ip(),
            ]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)->with('status', 'Invoice created successfully.');
    }

    public function show(Request $request, Invoice $invoice)
    {
        $invoice->load(['customer', 'items', 'receipts.paymentTransaction']);

        abort_if(
            $request->user()->role === 'customer' && $invoice->customer?->email !== $request->user()->email,
            403
        );

        return view('invoices.show', compact('invoice'));
    }
}
