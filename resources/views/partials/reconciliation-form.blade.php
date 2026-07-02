<form method="post" action="{{ route('reconciliations.store') }}">
    @csrf
    <div class="split">
        <div class="field">
            <label for="{{ $prefix ?? 'recon' }}_processor">Processor</label>
            <select id="{{ $prefix ?? 'recon' }}_processor" name="processor">
                <option>Stripe</option>
                <option>PayPal</option>
                <option>Flutterwave</option>
                <option>Manual</option>
            </select>
        </div>
        <div class="field">
            <label for="{{ $prefix ?? 'recon' }}_deposited_at">Deposit date</label>
            <input id="{{ $prefix ?? 'recon' }}_deposited_at" name="deposited_at" type="date" value="{{ now()->toDateString() }}">
        </div>
    </div>
    <div class="field">
        <label for="{{ $prefix ?? 'recon' }}_deposit_reference">Bank reference</label>
        <input id="{{ $prefix ?? 'recon' }}_deposit_reference" name="deposit_reference" value="DEP-{{ now()->format('YmdHis') }}">
    </div>
    <div class="split">
        <div class="field">
            <label for="{{ $prefix ?? 'recon' }}_amount">Deposit amount</label>
            <input id="{{ $prefix ?? 'recon' }}_amount" name="amount" type="number" step="0.01" value="96.80">
        </div>
        <div class="field">
            <label for="{{ $prefix ?? 'recon' }}_currency">Currency</label>
            <input id="{{ $prefix ?? 'recon' }}_currency" name="currency" maxlength="3" value="USD">
        </div>
    </div>
    <div class="field">
        <label for="{{ $prefix ?? 'recon' }}_exchange_rate">USD rate on deposit</label>
        <input id="{{ $prefix ?? 'recon' }}_exchange_rate" name="exchange_rate" type="number" step="0.000001" value="1">
    </div>
    <button class="secondary" type="submit">Run reconciliation</button>
</form>
