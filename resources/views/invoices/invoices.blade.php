
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 35px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #27272a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header {
            border-bottom: 3px solid #84cc16;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            color: #365314;
            margin: 0;
        }

        .subtitle {
            color: #71717a;
            margin-top: 4px;
        }

        .invoice-title {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }

        .invoice-number {
            text-align: right;
            color: #71717a;
        }

        .info-table td {
            width: 50%;
            padding: 5px 0;
            vertical-align: top;
        }

        .label {
            font-size: 9px;
            color: #71717a;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 25px 0 10px;
        }

        .items-table th {
            padding: 9px 6px;
            background: #365314;
            color: white;
            text-align: left;
            font-size: 9px;
        }

        .items-table td {
            padding: 9px 6px;
            border-bottom: 1px solid #e4e4e7;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .total-table {
            width: 50%;
            margin: 15px 0 0 auto;
        }

        .total-table td {
            padding: 8px 5px;
        }

        .grand-total td {
            border-top: 2px solid #84cc16;
            font-size: 13px;
            font-weight: bold;
            color: #365314;
        }

        .payment-box {
            margin-top: 25px;
            padding: 12px;
            background: #f7fee7;
            border: 1px solid #d9f99d;
        }

        .footer {
            margin-top: 35px;
            padding-top: 12px;
            border-top: 1px solid #e4e4e7;
            text-align: center;
            color: #71717a;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <p class="brand">MADURA MART</p>
                <div class="subtitle">Invoice Pesanan Pelanggan</div>
            </td>
            <td>
                <p class="invoice-title">INVOICE</p>
                <div class="invoice-number">
                    {{ $order->order_number }}
                </div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td>
                <div class="label">Ditagihkan kepada</div>
                <strong>{{ $order->customer?->name ?? 'Pelanggan' }}</strong><br>
                {{ $order->customer?->email ?? '-' }}<br>
                {{ $order->customer?->phone ?? '-' }}
            </td>
            <td>
                <div class="label">Informasi pesanan</div>
                Nomor: {{ $order->order_number }}<br>
                Tanggal:
                {{ $order->order_date?->format('d/m/Y H:i') ?? '-' }}<br>
                Status pesanan: {{ ucfirst($order->status) }}<br>
                Status pembayaran: {{ ucfirst($order->payment_status) }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 15px;">
                <div class="label">Alamat pengiriman</div>
                {{ $order->delivery_address ?: '-' }}
            </td>
        </tr>
    </table>

    <div class="section-title">Rincian Produk</div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 35%;">Produk</th>
                <th style="width: 15%;">SKU</th>
                <th class="center" style="width: 12%;">Jumlah</th>
                <th class="right" style="width: 16%;">Harga</th>
                <th class="right" style="width: 17%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->product?->name ?? 'Produk tidak tersedia' }}
                    </td>
                    <td>{{ $item->product?->sku ?? '-' }}</td>
                    <td class="center">
                        {{ $item->quantity }}
                        {{ $item->product?->unit ?? '' }}
                    </td>
                    <td class="right">
                        Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                    </td>
                    <td class="right">
                        Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">
                        Tidak ada item dalam pesanan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="total-table">
        <tr class="grand-total">
            <td>Total pembayaran</td>
            <td class="right">
                Rp {{ number_format((float) $order->total, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="payment-box">
        <strong>Informasi Pembayaran</strong><br>
        Metode:
        {{ $order->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}<br>
        Status: {{ strtoupper($order->payment_status) }}<br>
        <strong>PEMBAYARAN LUNAS</strong>
    </div>

    <div class="footer">
        Terima kasih telah berbelanja di Madura Mart.<br>
        Invoice ini dibuat secara otomatis oleh sistem.
    </div>
</body>
</html>
