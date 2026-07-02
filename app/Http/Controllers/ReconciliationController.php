<?php

namespace App\Http\Controllers;

use App\Services\ReconciliationService;
use Illuminate\Http\Request;

class ReconciliationController extends Controller
{
    public function store(Request $request, ReconciliationService $reconciliationService)
    {
        $data = $request->validate([
            'processor' => ['required', 'string', 'max:50'],
            'deposit_reference' => ['required', 'string', 'max:100', 'unique:bank_deposits,deposit_reference'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3'],
            'exchange_rate' => ['required', 'numeric', 'min:0.000001'],
            'deposited_at' => ['required', 'date'],
        ]);

        $reconciliation = $reconciliationService->createAndMatch($data);

        return redirect()
            ->route('dashboard')
            ->with('status', $reconciliation->status === 'matched'
                ? 'Deposit matched successfully.'
                : 'Discrepancy flagged for review.');
    }
}
