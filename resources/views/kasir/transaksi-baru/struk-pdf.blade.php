
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
            background: #ffffff;
            color: #000000;
            font-family: "DejaVu Sans Mono", monospace;
            font-size: 8px;
            line-height: 1.45;
        }

        .receipt {
            width: 80mm;
            padding: 4mm;
            margin: 0;
            background: #ffffff;
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

        .line {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .line .left,
        .line .right {
            display: table-cell;
            vertical-align: top;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .line .left {
            width: 38%;
            text-align: left;
        }

        .line .right {
            width: 62%;
            text-align: right;
        }

        .separator {
            border-top: 1px dashed #aaaaaa;
            margin: 6px 0;
        }

        .item {
            margin-bottom: 5px;
            page-break-inside: avoid;
        }

        .item-name {
            margin-bottom: 2px;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .item-detail .left {
            width: 58%;
        }

        .item-detail .right {
            width: 42%;
        }

        .total {
            font-size: 9px;
            font-weight: bold;
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

        <div class="line">
            <span class="left">No</span>
            <span class="right">{{ $sale->invoice }}</span>
        </div>

        <div class="line">
            <span class="left">Kasir</span>
            <span class="right">{{ $sale->user->name ?? '-' }}</span>
        </div>

        <div class="line">
            <span class="left">Pelanggan</span>
            <span class="right">{{ $sale->customer->name ?? 'Umum' }}</span>
        </div>

        <div class="line">
            <span class="left">Tanggal</span>
            <span class="right">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</span>
        </div>

        <div class="separator"></div>

        @foreach ($sale->items as $item)
            <div class="item">
                <div class="item-name">
                    {{ $item->product->name ?? 'Produk tidak tersedia' }}
                </div>

                <div class="line item-detail">
                    <span class="left">
                        {{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                    </span>
                    <span class="right">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        @endforeach

        <div class="separator"></div>

        <div class="summary">
            <div class="line">
                <span class="left">Subtotal</span>
                <span class="right">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
            </div>

            @if ((float) $sale->diskon > 0)
                <div class="line">
                    <span class="left">Diskon</span>
                    <span class="right">Rp {{ number_format($sale->diskon, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="line">
                <span class="left">Pajak</span>
                <span class="right">Rp {{ number_format($sale->tax, 0, ',', '.') }}</span>
            </div>

            <div class="separator"></div>

            <div class="line total">
                <span class="left">TOTAL</span>
                <span class="right">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
            </div>

            <div class="line">
                <span class="left">Bayar</span>
                <span class="right">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
            </div>

            <div class="line">
                <span class="left">Kembali</span>
                <span class="right">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            Terima kasih 🙏
        </div>
    </div>
</body>
</html>
