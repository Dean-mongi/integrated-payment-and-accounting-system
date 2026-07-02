<form method="post" action="{{ route('transactions.store') }}">
    @csrf
    <div class="split">
        <div class="field">
            <label for="{{ $prefix ?? 'transaction' }}_type">Type</label>
            <select id="{{ $prefix ?? 'transaction' }}_type" name="type">
                <option value="sale">Sale</option>
                <option value="renewal">Subscription renewal</option>
                <option value="payout">Outbound payout</option>
            </select>
        </div>
        <div class="field">
            <label for="{{ $prefix ?? 'transaction' }}_provider">Processor</label>
            <select id="{{ $prefix ?? 'transaction' }}_provider" name="provider">
                <option>Stripe</option>
                <option>PayPal</option>
                <option>Flutterwave</option>
                <option>Manual</option>
            </select>
        </div>
    </div>
    <div class="field">
        <label for="{{ $prefix ?? 'transaction' }}_provider_reference">Processor reference</label>
        <input id="{{ $prefix ?? 'transaction' }}_provider_reference" name="provider_reference" placeholder="Optional auto-generated">
    </div>
    <div class="split">
        <div class="field">
            <label for="{{ $prefix ?? 'transaction' }}_gross_amount">Gross amount</label>
            <input id="{{ $prefix ?? 'transaction' }}_gross_amount" name="gross_amount" type="number" step="0.01" value="100.00">
        </div>
        <div class="field">
            <label for="{{ $prefix ?? 'transaction' }}_fee_amount">Processor fee</label>
            <input id="{{ $prefix ?? 'transaction' }}_fee_amount" name="fee_amount" type="number" step="0.01" value="3.20">
        </div>
    </div>
    <div class="split">
        <div class="field">
            <label for="{{ $prefix ?? 'transaction' }}_currency">Currency</label>
            <input id="{{ $prefix ?? 'transaction' }}_currency" name="currency" maxlength="3" value="USD">
        </div>
        <div class="field">
            <label for="{{ $prefix ?? 'transaction' }}_exchange_rate">USD rate at transaction time</label>
            <input id="{{ $prefix ?? 'transaction' }}_exchange_rate" name="exchange_rate" type="number" step="0.000001" value="1">
        </div>
    </div>
    <div class="split">
        <div class="field">
            <label for="{{ $prefix ?? 'transaction' }}_customer_name">Customer</label>
            <input id="{{ $prefix ?? 'transaction' }}_customer_name" name="customer_name" value="Acme Stores">
        </div>
        <div class="field">
            <label for="{{ $prefix ?? 'transaction' }}_customer_email">Email</label>
            <input id="{{ $prefix ?? 'transaction' }}_customer_email" name="customer_email" type="email" value="billing@acme.test">
        </div>
    </div>
    <button type="submit">Post to ledger</button>
</form>
