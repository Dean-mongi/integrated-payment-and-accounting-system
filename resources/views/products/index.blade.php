@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Products</h2>
            <p>Maintain billable items, stock levels, prices, and invoice-ready product records.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('invoices.create') }}">Use on invoice</a>
            <a class="link-button" href="{{ route('reports') }}">Sales report</a>
        </div>
    </div>

    <section class="cards">
        <div class="card"><div class="label">Products</div><div class="value">{{ $summary['products'] }}</div></div>
        <div class="card"><div class="label">Active</div><div class="value">{{ $summary['active'] }}</div></div>
        <div class="card"><div class="label">Stock Units</div><div class="value">{{ $summary['stock'] }}</div></div>
        <div class="card"><div class="label">Inventory Value</div><div class="value money">${{ number_format($summary['inventory_value'], 2) }}</div></div>
    </section>

    <section class="panel" style="margin-top:16px;">
        <h2>Product Catalogue</h2>
        <div class="table-scroll">
            <table>
                <thead><tr><th>SKU</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category ?? 'General' }}</td>
                            <td class="money">${{ number_format($product->unit_price, 2) }}</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td><span class="pill {{ $product->status !== 'active' ? 'bad' : '' }}">{{ $product->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No products configured yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
    </section>
@endsection
