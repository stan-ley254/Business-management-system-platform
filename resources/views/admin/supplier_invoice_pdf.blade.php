<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Draft Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; font-size: 12px; margin: 0; padding: 24px; }
        .header { text-align: center; margin-bottom: 18px; }
        .header img { max-width: 120px; max-height: 60px; margin-bottom: 12px; }
        .business-name { font-size: 20px; font-weight: bold; margin-bottom: 6px; }
        .meta { margin-top: 10px; margin-bottom: 18px; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; padding: 8px 0; }
        .meta-row { display: flex; justify-content: space-between; margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 6px 0; text-align: left; }
        th { font-size: 11px; text-transform: uppercase; color: #4b5563; }
        .amount { text-align: right; }
        .total { margin-top: 16px; text-align: right; font-size: 18px; font-weight: bold; }
        .footer { margin-top: 18px; text-align: center; border-top: 1px solid #d1d5db; padding-top: 10px; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        @php
            $logoPath = null;
            if (!empty($business->logo_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($business->logo_path)) {
                $logoPath = \Illuminate\Support\Facades\Storage::disk('public')->path($business->logo_path);
            }
        @endphp
        @if($logoPath)
            <img src="{{ $logoPath }}" alt="Business Logo">
        @endif
        <div class="business-name">{{ $business->name ?? 'Business' }}</div>
        @if(!empty($business->address)) <div>{{ $business->address }}</div> @endif
        @if(!empty($business->phone)) <div>{{ $business->phone }}</div> @endif
    </div>

    <div class="meta">
        <div class="meta-row"><span>Draft Invoice #</span><strong>{{ $invoice->invoice_number ?: $invoice->id }}</strong></div>
        <div class="meta-row"><span>Date</span><strong>{{ $invoice->created_at?->format('d M Y, H:i') }}</strong></div>
        <div class="meta-row"><span>Supplier</span><strong>{{ $invoice->supplier->supplier_name ?? '—' }}</strong></div>
        <div class="meta-row"><span>Status</span><strong>{{ ucfirst($invoice->status) }}</strong></div>
    </div>

    <table>
        <thead>
            <tr><th>Product</th><th>Qty</th><th>Cost Price</th><th class="amount">Subtotal</th></tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                @php
                    $name = $item->product_name ?? $item->supplierProduct->supplier_product_name ?? 'Item';
                    $subtotal = (float) $item->cost_price * (int) $item->quantity;
                @endphp
                <tr>
                    <td>{{ $name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format((float) $item->cost_price, 2) }}</td>
                    <td class="amount">{{ number_format($subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">Draft Total: {{ number_format((float) $invoice->items->sum(fn ($item) => $item->cost_price * $item->quantity), 2) }}</div>
    <div class="footer">Draft invoice for reference before confirmation.</div>
</body>
</html>
