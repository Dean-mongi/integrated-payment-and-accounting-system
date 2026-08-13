<form method="post" action="{{ route('transactions.store') }}">
    @csrf
    <div class="split">
        <x-form-select :id="($prefix ?? 'transaction').'_type'" name="type" label="Type" :value="old('type', 'sale')" :options="['sale' => 'Sale', 'renewal' => 'Subscription renewal', 'payout' => 'Outbound payout']" />
        <x-form-select :id="($prefix ?? 'transaction').'_provider'" name="provider" label="Processor" :value="old('provider', 'Stripe')" :options="['Stripe' => 'Stripe', 'PayPal' => 'PayPal', 'Flutterwave' => 'Flutterwave', 'Manual' => 'Manual']" />
    </div>
    <x-form-input :id="($prefix ?? 'transaction').'_provider_reference'" name="provider_reference" label="Processor reference" :value="old('provider_reference')" placeholder="Optional auto-generated" />
    <div class="split">
        <x-form-input :id="($prefix ?? 'transaction').'_gross_amount'" name="gross_amount" label="Gross amount" type="number" step="0.01" value="100.00" required />
        <x-form-input :id="($prefix ?? 'transaction').'_fee_amount'" name="fee_amount" label="Processor fee" type="number" step="0.01" value="3.20" />
    </div>
    <div class="split">
        <x-form-input :id="($prefix ?? 'transaction').'_currency'" name="currency" label="Currency" maxlength="3" value="USD" required />
        <x-form-input :id="($prefix ?? 'transaction').'_exchange_rate'" name="exchange_rate" label="USD rate at transaction time" type="number" step="0.000001" value="1" required />
    </div>
    <div class="split">
        <x-form-input :id="($prefix ?? 'transaction').'_customer_name'" name="customer_name" label="Customer" value="Acme Stores" />
        <x-form-input :id="($prefix ?? 'transaction').'_customer_email'" name="customer_email" label="Email" type="email" value="billing@acme.test" />
    </div>
    <x-button>Post to ledger</x-button>
</form>
