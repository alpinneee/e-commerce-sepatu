<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .invoice-header {
            background: #f8f9fa;
            padding: 30px;
            border-bottom: 2px solid #e9ecef;
        }
        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }
        .company-logo {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .invoice-number {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .invoice-date {
            font-size: 14px;
            color: #6b7280;
        }
        .party-info {
            display: flex;
            justify-content: space-between;
            padding: 30px;
            border-bottom: 1px solid #e5e7eb;
        }
        .party-section {
            flex: 1;
        }
        .party-section h3 {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .party-details {
            color: #6b7280;
            font-size: 14px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        .items-table th {
            background: #f3f4f6;
            padding: 15px 10px;
            text-align: left;
            font-weight: bold;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }
        .items-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .items-table tr:nth-child(even) {
            background: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-section {
            padding: 30px;
            background: #f8f9fa;
        }
        .totals-table {
            width: 100%;
            max-width: 400px;
            margin-left: auto;
        }
        .totals-table td {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .totals-table .total-row {
            font-weight: bold;
            font-size: 18px;
            border-top: 2px solid #374151;
            border-bottom: 2px solid #374151;
        }
        .payment-info {
            padding: 30px;
            border-top: 1px solid #e5e7eb;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-failed {
            background: #fee2e2;
            color: #991b1b;
        }
        .footer {
            padding: 20px 30px;
            background: #1f2937;
            color: white;
            text-align: center;
            font-size: 12px;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .print-button:hover {
            background: #1d4ed8;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .print-button {
                display: none;
            }
            .invoice-container {
                border: none;
                border-radius: 0;
                box-shadow: none;
            }
        }
        @media (max-width: 768px) {
            .company-info, .party-info {
                flex-direction: column;
            }
            .party-section {
                margin-bottom: 20px;
            }
            .invoice-details {
                text-align: left;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print Invoice</button>
    
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div>
                    <div class="company-logo">🏪 Toko Sepatu</div>
                    <div style="margin-top: 10px; color: #6b7280; font-size: 14px;">
                        Jl. Sepatu Raya No. 123<br>
                        Jakarta Selatan, 12345<br>
                        Telp: (021) 123-4567<br>
                        Email: info@tokosepatu.com
                    </div>
                </div>
                <div class="invoice-details">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">#{{ $order->order_number }}</div>
                    <div class="invoice-date">{{ $order->created_at->format('d M Y') }}</div>
                    <div style="margin-top: 10px;">
                        <span class="status-badge 
                            @if($order->payment_status === 'paid') status-paid
                            @elseif($order->payment_status === 'pending') status-pending
                            @else status-failed @endif">
                            {{ $order->payment_status_label }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Party Information -->
        <div class="party-info">
            <div class="party-section">
                <h3>📦 KEPADA:</h3>
                @php
                    $shippingAddress = $order->shipping_address_object;
                @endphp
                <div class="party-details">
                    <strong>{{ $shippingAddress->name }}</strong><br>
                    {{ $shippingAddress->address }}<br>
                    {{ $shippingAddress->city }}, {{ $shippingAddress->province }} {{ $shippingAddress->postal_code }}<br>
                    <strong>Telp:</strong> {{ $shippingAddress->phone }}<br>
                    <strong>Email:</strong> {{ $shippingAddress->email }}
                </div>
            </div>
            
            <div class="party-section">
                <h3>🚚 PENGIRIMAN:</h3>
                <div class="party-details">
                    @if($order->shipping_expedition_name)
                        <strong>Ekspedisi:</strong> {{ $order->shipping_expedition_name }}<br>
                        <strong>Estimasi:</strong> {{ $order->shipping_estimation }}<br>
                    @endif
                    <strong>Metode Pembayaran:</strong> {{ $order->payment_method_label }}<br>
                    <strong>Status Pesanan:</strong> 
                    <span class="status-badge 
                        @if($order->status === 'delivered') status-paid
                        @elseif($order->status === 'pending') status-pending
                        @else status-pending @endif">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Produk</th>
                    <th style="width: 80px;" class="text-center">Qty</th>
                    <th style="width: 120px;" class="text-right">Harga</th>
                    <th style="width: 120px;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product->name }}</strong><br>
                        <small style="color: #6b7280;">SKU: {{ $item->product->sku }}</small>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($order->discount_amount > 0)
                <tr style="color: #059669;">
                    <td><strong>Diskon:</strong></td>
                    <td class="text-right">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Ongkos Kirim:</strong></td>
                    <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                @if($order->cod_fee > 0)
                <tr style="color: #d97706;">
                    <td><strong>Biaya COD:</strong></td>
                    <td class="text-right">Rp {{ number_format($order->cod_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td><strong>TOTAL PEMBAYARAN:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Payment Information -->
        @if($order->notes)
        <div class="payment-info">
            <h3 style="margin-bottom: 15px; color: #374151;">📝 Catatan:</h3>
            <div style="background: #f3f4f6; padding: 15px; border-radius: 6px; color: #6b7280;">
                {{ $order->notes }}
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div>Invoice ini dibuat secara otomatis oleh sistem Toko Sepatu</div>
            <div style="margin-top: 5px;">Terima kasih telah berbelanja dengan kami! 🙏</div>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html> 