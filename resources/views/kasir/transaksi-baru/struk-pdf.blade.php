
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $sale->invoice }}</title>

    <style>
        @page {
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 80mm;
            font-family: "DejaVu Sans Mono", monospace;
            font-size: 8px;
            line-height: 1.45;
            color: #000000;
            background: #ffffff;
        }

        .receipt {
            width: 74mm;
            margin: 0 3mm;
            padding: 4mm 0;
        }

        .header {
            text-align: center;
            padding-bottom: 6px;
            margin-bottom: 6px;
            border-bottom: 1px dashed #aaaaaa;
        }

        .store-name {
            margin: 0 0 2px;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            font-weight: bold;
        }

        .header p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            padding: 1px 0;
            vertical-align: top;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .label {
            width: 30%;
            text-align: left;
        }

        .value {
            width: 70%;
            text-align: right;
            word-wrap: break-word;
        }

        .separator {
            border-top: 1px dashed #aaaaaa;
            margin: 6px 0;
        }

        .product {
            margin-bottom: 5px;
            page-break-inside: avoid;
        }

        .product-name {
            margin-bottom: 2px;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .product-table .quantity {
            width: 58%;
            text-align: left;
        }

        .product-table .amount {
            width: 42%;
            text-align: right;
            word-wrap: break-word;
        }

        .total td {
            font-size: 9px;
            font-weight: bold;
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .footer {
            border-top: 1px dashed #aaaaaa;
            margin-top: 6px;
            padding-top: 7px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <p class="store-name">WAR-MART</p>
            <p>Jl. Contoh No. 123</p>
            <p>Jakarta Selatan</p>
        </div>

        <table>
            <tr>
                <td class="label">No</td>
                <td class="value">{{ $sale->invoice }}</td>
            </tr>
            <tr>
                <td class="label">Kasir</td>
                <td class="value">{{ $sale->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Pelanggan</td>
                <td class="value">{{ $sale->customer->name ?? 'Umum' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td class="value">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</td>
            </tr>
        </table>

        <div class="separator"></div>

        @foreach ($sale->items as $item)
            <div class="product">
                <div class="product-name">
                    {{ $item->product->name ?? 'Produk tidak tersedia' }}
                </div>

                <table class="product-table">
                    <tr>
                        <td class="quantity">
                            {{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                        </td>
                        <td class="amount">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach

        <div class="separator"></div>

        <table>
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
            </tr>

            @if ((float) $sale->diskon > 0)
                <tr>
                    <td class="label">Diskon</td>
                    <td class="value">Rp {{ number_format($sale->diskon, 0, ',', '.') }}</td>
                </tr>
            @endif

            <tr>
                <td class="label">Pajak</td>
                <td class="value">Rp {{ number_format($sale->tax, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="separator"></div>

        <table>
            <tr class="total">
                <td class="label">TOTAL</td>
                <td class="value">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Bayar</td>
                <td class="value">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Kembali</td>
                <td class="value">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="footer">
            Terima kasih 🙏
        </div>
    </div>
</body>
</html>
