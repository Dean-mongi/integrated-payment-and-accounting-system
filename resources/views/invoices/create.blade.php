@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Create Invoice</h2>
            <p>Add a customer, product or service lines, due date, and currency.</p>
        </div>
        <a class="link-button" href="{{ route('invoices.index') }}">Back to invoices</a>
    </div>

    <section class="panel">
        <form method="post" action="{{ route('invoices.store') }}">
            @csrf
            <div class="split">
                <div class="field"><label for="customer_name">Customer name</label><input id="customer_name" name="customer_name" value="{{ old('customer_name', 'Acme Stores') }}" required></div>
                <div class="field"><label for="customer_email">Customer email</label><input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email', 'customer@example.com') }}" required></div>
            </div>
            <div class="split">
                <div class="field"><label for="currency">Currency</label><input id="currency" name="currency" maxlength="3" value="{{ old('currency', 'USD') }}" required></div>
                <div class="field"><label for="due_date">Due date</label><input id="due_date" name="due_date" type="date" value="{{ old('due_date', now()->addDays(7)->toDateString()) }}" required></div>
            </div>
            <div class="split">
                <div class="field"><label for="description_0">Product or service</label><input id="description_0" name="description[]" value="{{ old('description.0', 'Consulting service') }}" required></div>
                <div class="split">
                    <div class="field"><label for="quantity_0">Quantity</label><input id="quantity_0" name="quantity[]" type="number" step="0.01" value="{{ old('quantity.0', '1') }}" required></div>
                    <div class="field"><label for="unit_price_0">Unit price</label><input id="unit_price_0" name="unit_price[]" type="number" step="0.01" value="{{ old('unit_price.0', '100.00') }}" required></div>
                </div>
            </div>
            <div class="field"><label for="notes">Notes</label><input id="notes" name="notes" value="{{ old('notes') }}"></div>
            <button type="submit">Create invoice</button>
        </form>
    </section>
@endsection
