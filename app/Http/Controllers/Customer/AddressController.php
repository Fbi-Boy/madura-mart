<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    /**
     * Tampilkan halaman daftar alamat + form tambah.
     */
    public function edit(): View
    {
        $customer = auth()->user()->customer;

        $addresses = $customer
            ? CustomerAddress::where('customer_id', $customer->id)
                ->orderByDesc('is_default')
                ->orderByDesc('id')
                ->get()
            : collect();

        return view('customer.address.edit', compact('customer', 'addresses'));
    }

    /**
     * Handle tambah / set default / hapus alamat.
     */
    public function update(Request $request)
    {
        $customer = auth()->user()->customer;

        if (!$customer) {
            return back()->withErrors(['general' => 'Profil customer belum terhubung.']);
        }

        $mode = $request->input('mode');

        // ================= ADD =================
        if ($mode === 'add') {
            $data = $request->validate([
                'label'          => ['required', 'string', 'max:50'],
                'recipient_name' => ['required', 'string', 'max:100'],
                'phone'          => ['required', 'string', 'max:30'],
                'city'           => ['required', 'string', 'max:100'],
                'address'        => ['required', 'string', 'max:1000'],
                'is_default'     => ['nullable', 'boolean'],
            ]);

            // Kalau ini jadi default, reset semua default customer
            if ($request->boolean('is_default')) {
                CustomerAddress::where('customer_id', $customer->id)
                    ->update(['is_default' => false]);
            }

            CustomerAddress::create([
                'customer_id'    => $customer->id,
                'label'          => $data['label'],
                'recipient_name' => $data['recipient_name'],
                'phone'          => $data['phone'],
                'city'           => $data['city'],
                'address'        => $data['address'],
                'is_default'     => $request->boolean('is_default'),
            ]);

            return back()->with('status', 'address-created');
        }

        // ================= SET DEFAULT =================
        if ($mode === 'default') {
            $request->validate([
                'address_id' => ['required', 'exists:customer_addresses,id'],
            ]);

            // Reset semua default
            CustomerAddress::where('customer_id', $customer->id)
                ->update(['is_default' => false]);

            // Set yang dipilih jadi default
            CustomerAddress::where('id', $request->address_id)
                ->where('customer_id', $customer->id)
                ->update(['is_default' => true]);

            return back()->with('status', 'address-default-updated');
        }

        // ================= DELETE =================
        if ($mode === 'delete') {
            $request->validate([
                'address_id' => ['required', 'exists:customer_addresses,id'],
            ]);

            CustomerAddress::where('id', $request->address_id)
                ->where('customer_id', $customer->id)
                ->delete();

            return back()->with('status', 'address-deleted');
        }

        return back();
    }
}
