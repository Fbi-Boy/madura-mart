<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $sale->invoice }}</title>
    <style>
        @page {
            margin: 4mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            width: 72mm;
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            padding-bottom: 8px;
            border-bottom: 1px solid #222;
        }

        .header h1 {
            margin: 0 0 3px;
            font-size: 17px;
            letter-spacing: 1px;
        }

        .header p {
            margin: 2px 0;
            color: #555;
            font-size: 8px;
        }

        .title {
            text-align: center;
            margin: 9px 0;
        }

        .title h2 {
            margin: 0 0 3px;
            font-size: 11px;
        }

        .title p {
            margin: 0;
            font-size: 8px;
            overflow-wrap: anywhere;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .information td {
            padding: 2px 0;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        .information td:first-child {
            width: 31%;
            color: #666;
            padding-right: 3px;
        }

        .items {
            margin-top: 9px;
            border-top: 1px dashed #777;
        }

        .items td {
            padding: 5px 0;
            border-bottom: 1px dashed #ddd;
            vertical-align: top;
        }

        .item-name {
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .item-detail {
            display: table;
            width: 100%;
            margin-top: 2px;
            color: #555;
            font-size: 8px;
        }

        .item-detail .left {
            display: table-cell;
            text-align: left;
            width: 58%;
        }

        .item-detail .right {
            display: table-cell;
            text-align: right;
            width: 42%;
        }

        .right {
            text-align: right;
        }

        .summary {
            margin-top: 8px;
        }

        .summary td {
            padding: 2px 0;
        }

        .summary td:first-child {
            color: #666;
        }

        .summary .total td {
            padding-top: 6px;
            border-top: 1px solid #222;
            font-size: 11px;
            font-weight: bold;
            color: #222;
        }

        .payment {
            margin-top: 9px;
            padding: 7px;
            background: #f7f7f7;
        }

        .payment td {
            padding: 2px 0;
        }

        .footer {
            text-align: center;
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px dashed #aaa;
            color: #666;
            font-size: 8px;
        }

        .footer strong {
            display: block;
            font-size: 9px;
            color: #222;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MADURA MART</h1>
        <p>Struk Transaksi Penjualan</p>
        <p>Dokumen transaksi resmi dari sistem kasir</p>
    </div>

    <div class="title">
        <h2>INVOICE PENJUALAN</h2>
        <p>Nomor: {{ $sale->invoice }}</p>
    </div>

    <table class="information">
        <tr>
            <td>Tanggal</td>
            <td>: {{ $sale->sale_date ? $sale->sale_date->format('d/m/Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>: {{ $sale->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>: {{ $sale->customer->name ?? 'Umum' }}</td>
        </tr>
        <tr>
            <td>Pembayaran</td>
            <td>: {{ strtoupper($sale->payment_method ?? '-') }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: {{ strtoupper($sale->status ?? '-') }}</td>
        </tr>
    </table>

    <table class="items">
        <tbody>
            @forelse ($sale->items as $index => $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $index + 1 }}. {{ $item->product->name ?? 'Produk tidak tersedia' }}</div>
                        <div class="item-detail">
                            <span class="left">{{ $item->quantity }} x Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</span>
                            <span class="right">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td style="text-align:center;">Tidak ada detail barang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td>Subtotal</td>
            <td class="right">Rp {{ number_format((float) $sale->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="right">Rp {{ number_format((float) ($sale->diskon ?? 0), 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Pajak</td>
            <td class="right">Rp {{ number_format((float) $sale->tax, 0, ',', '.') }}</td>
        </tr>
        <tr class="total">
            <td>TOTAL</td>
            <td class="right">Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="payment">
        <table>
            <tr>
                <td>Jumlah Dibayar</td>
                <td class="right">Rp {{ number_format((float) $sale->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td class="right">Rp {{ number_format((float) $sale->change_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <strong>Terima kasih telah berbelanja di Madura Mart.</strong>
        Simpan invoice ini sebagai bukti transaksi.
        <br>
        Dokumen dibuat secara otomatis oleh sistem kasir.
    </div>
</body>
</html>
