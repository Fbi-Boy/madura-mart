<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemSettingController extends Controller
{
    private const DEFINITIONS = [
        'store_name' => [
            'label' => 'Nama Toko',
            'type' => 'text',
            'description' => 'Nama yang digunakan sebagai identitas toko.',
            'default' => 'Madura Mart',
        ],
        'store_phone' => [
            'label' => 'Nomor Telepon Toko',
            'type' => 'text',
            'description' => 'Nomor kontak utama toko.',
            'default' => '',
        ],
        'store_email' => [
            'label' => 'Email Toko',
            'type' => 'email',
            'description' => 'Email kontak utama toko.',
            'default' => '',
        ],
        'tax_percent' => [
            'label' => 'Pajak (%)',
            'type' => 'number',
            'description' => 'Persentase pajak default transaksi.',
            'default' => '0',
        ],
        'discount_percent' => [
            'label' => 'Diskon Default (%)',
            'type' => 'number',
            'description' => 'Persentase diskon default yang dapat digunakan transaksi.',
            'default' => '0',
        ],
        'shipping_fee' => [
            'label' => 'Biaya Pengiriman',
            'type' => 'number',
            'description' => 'Biaya pengiriman default dalam rupiah.',
            'default' => '0',
        ],
    ];

    public function index(): View
    {
        $settings = collect(self::DEFINITIONS)->mapWithKeys(function (array $definition, string $key) {
            $setting = SystemSetting::query()->where('key', $key)->first();

            return [$key => [
                ...$definition,
                'value' => $setting?->value ?? $definition['default'],
            ]];
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
                default => ['nullable', 'string', 'max:255'],
            };
        }

        $data = $request->validate($rules);

        foreach (self::DEFINITIONS as $key => $definition) {
            SystemSetting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data[$key] ?? '',
                    'type' => $definition['type'],
                    'description' => $definition['description'],
                ],
            );
        }

        return to_route('admin.settings.index')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
