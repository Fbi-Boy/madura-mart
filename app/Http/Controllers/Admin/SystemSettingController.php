<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemSettingController extends Controller
{
    private const DEFINITIONS = [
        'store_name' => ['label' => 'Nama Toko', 'type' => 'text', 'description' => 'Nama identitas toko.', 'default' => 'Madura Mart', 'group' => 'Identitas Toko'],
        'store_phone' => ['label' => 'Nomor Telepon Toko', 'type' => 'text', 'description' => 'Kontak utama toko.', 'default' => '', 'group' => 'Identitas Toko'],
        'store_email' => ['label' => 'Email Toko', 'type' => 'email', 'description' => 'Email kontak utama toko.', 'default' => '', 'group' => 'Identitas Toko'],
        'currency' => ['label' => 'Mata Uang', 'type' => 'text', 'description' => 'Kode mata uang untuk transaksi.', 'default' => 'IDR', 'group' => 'Transaksi'],
        'order_prefix' => ['label' => 'Prefix Nomor Pesanan', 'type' => 'text', 'description' => 'Prefix untuk identitas nomor pesanan.', 'default' => 'MM-', 'group' => 'Transaksi'],
        'minimum_order' => ['label' => 'Minimum Belanja', 'type' => 'number', 'description' => 'Nilai minimum checkout customer.', 'default' => '0', 'group' => 'Transaksi'],
        'tax_percent' => ['label' => 'Pajak (%)', 'type' => 'number', 'description' => 'Persentase pajak default.', 'default' => '0', 'group' => 'Transaksi'],
        'discount_percent' => ['label' => 'Diskon Default (%)', 'type' => 'number', 'description' => 'Persentase diskon default.', 'default' => '0', 'group' => 'Transaksi'],
        'payment_methods' => ['label' => 'Metode Pembayaran', 'type' => 'text', 'description' => 'Pisahkan metode dengan koma, misalnya QRIS, Transfer Bank, COD.', 'default' => 'QRIS, Transfer Bank', 'group' => 'Pembayaran'],
        'bank_name' => ['label' => 'Bank Pembayaran', 'type' => 'text', 'description' => 'Nama bank tujuan transfer.', 'default' => '', 'group' => 'Pembayaran'],
        'bank_account' => ['label' => 'Nomor Rekening', 'type' => 'text', 'description' => 'Nomor rekening tujuan transfer.', 'default' => '', 'group' => 'Pembayaran'],
        'shipping_enabled' => ['label' => 'Pengiriman Aktif', 'type' => 'boolean', 'description' => 'Aktifkan opsi pengiriman pada checkout.', 'default' => '1', 'group' => 'Pengiriman'],
        'shipping_fee' => ['label' => 'Biaya Pengiriman', 'type' => 'number', 'description' => 'Biaya pengiriman default dalam rupiah.', 'default' => '0', 'group' => 'Pengiriman'],
    ];

    public function index(): View
    {
        $settings = collect(self::DEFINITIONS)->mapWithKeys(function (array $definition, string $key) {
            $setting = SystemSetting::query()->where('key', $key)->first();

            return [$key => [...$definition, 'value' => $setting?->value ?? $definition['default']]];
        });

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];

        foreach (self::DEFINITIONS as $key => $definition) {
            $rules[$key] = match ($definition['type']) {
                'email' => ['nullable', 'email', 'max:255'],
                'number' => ['nullable', 'numeric', 'min:0', 'max:1000000000'],
                'boolean' => ['nullable', 'boolean'],
                default => ['nullable', 'string', 'max:255'],
            };
        }

        $data = $request->validate($rules);

        foreach (self::DEFINITIONS as $key => $definition) {
            SystemSetting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'value' => $definition['type'] === 'boolean'
                        ? ($request->boolean($key) ? '1' : '0')
                        : ($data[$key] ?? ''),
                    'type' => $definition['type'],
                    'description' => $definition['description'],
                ],
            );
        }

        ActivityLogService::record(
            'system-settings.updated',
            'Pengaturan sistem diperbarui oleh Super Admin.',
            null,
            ['keys' => array_keys(self::DEFINITIONS)],
            $request,
        );

        return to_route('admin.settings.index')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
