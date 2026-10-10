<x-app-layout>
<div class="flex justify-center">
    <div class="bg-white text-black rounded-xl p-6 font-mono text-[12px] leading-relaxed border border-black/10 w-full max-w-sm">
        <div class="text-center border-b border-dashed border-black/30 pb-2 mb-2">
            <p class="text-base font-black">WAR-MART</p>
            <p>Jl. Contoh No. 123</p>
            <p>Jakarta Selatan</p>
        </div>
        <div class="flex justify-between"><span>No</span><span>{{ $sale->invoice }}</span></div>
        <div class="flex justify-between"><span>Kasir</span><span>{{ $sale->user->name }}</span></div>
        <div class="flex justify-between"><span>Pelanggan</span><span>{{ $sale->customer->name ?? 'Umum' }}</span></div>
        <div class="flex justify-between"><span>Tanggal</span><span>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</span></div>
        <div class="border-t border-dashed border-black/30 my-2"></div>

        @foreach($sale->items as $item)
            <div class="mb-1">
                <p>{{ $item->product->name }}</p>
                <div class="flex justify-between">
                    <span>  {{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach

        <div class="border-t border-dashed border-black/30 my-2"></div>
        <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span></div>
        <div class="flex justify-between"><span>Pajak</span><span>Rp {{ number_format($sale->tax, 0, ',', '.') }}</span></div>
        <div class="border-t border-dashed border-black/30 my-2"></div>
        <div class="flex justify-between font-black text-sm"><span>TOTAL</span><span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span></div>
        <div class="flex justify-between"><span>Bayar</span><span>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span></div>
        <div class="flex justify-between"><span>Kembali</span><span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span></div>
        <div class="border-t border-dashed border-black/30 mt-2 pt-2 text-center">
            <p>Terima kasih 🙏</p>
        </div>
    </div>
</div>
</x-app-layout>
