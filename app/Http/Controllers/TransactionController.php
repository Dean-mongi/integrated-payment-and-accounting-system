<?php

namespace App\Http\Controllers;

use App\Services\LedgerService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request, LedgerService $ledgerService)
    {
        $data = $request->validate([
            'type' => ['required', 'in:sale,renewal,payout'],
            'provider' => ['required', 'string', 'max:50'],
            'provider_reference' => ['nullable', 'string', 'max:100', 'unique:payment_transactions,provider_reference'],
            'gross_amount' => ['required', 'numeric', 'min:0.01'],
            'fee_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'exchange_rate' => ['required', 'numeric', 'min:0.000001'],
            'customer_name' => ['nullable', 'required_if:type,sale,renewal', 'string', 'max:120'],
            'customer_email' => ['nullable', 'email', 'max:120'],
        ]);

        $ledgerService->recordTransaction($data);

        return redirect()->route('dashboard')->with('status', 'Transaction posted and ledger entries balanced.');
    }
}
