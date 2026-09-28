<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function edit(Request $request): View
    {
        $customer = $this->customer($request);
        $addresses = $customer->addresses()->orderByDesc('is_default')->latest('id')->get();

        return view('customer.address.edit', compact('customer', 'addresses'));
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = $this->customer($request);
        $mode = $request->input('mode', 'legacy');

        if ($mode === 'default') {
            $data = $request->validate(['address_id' => ['required', 'integer']]);
            $address = $customer->addresses()->findOrFail($data['address_id']);
            DB::transaction(function () use ($customer, $address): void {
                $customer->addresses()->update(['is_default' => false]);
                $address->update(['is_default' => true]);
            });
            return back()->with('status', 'address-default-updated');
        }

        if ($mode === 'delete') {
            $data = $request->validate(['address_id' => ['required', 'integer']]);
            $address = $customer->addresses()->findOrFail($data['address_id']);
            DB::transaction(function () use ($customer, $address): void {
                $wasDefault = $address->is_default;
                $address->delete();
                if ($wasDefault) {
                    $customer->addresses()->latest('id')->first()?->update(['is_default' => true]);
                }
            });
            return back()->with('status', 'address-deleted');
        }

        // Keep the existing single-address profile endpoint backward compatible.
        if ($mode === 'legacy') {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'phone' => ['required', 'string', 'max:30'],
                'address' => ['required', 'string', 'max:500'],
                'city' => ['required', 'string', 'max:100'],
            ]);

            $customer->update($data);

            $default = $customer->addresses()->where('is_default', true)->first();
            if ($default) {
                $default->update([
                    'recipient_name' => $data['name'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'city' => $data['city'],
                ]);
            }

            return to_route('customer.address.edit')->with('status', 'address-updated');
        }

        $data = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:1000'],
            'city' => ['required', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($customer, $data): void {
            if (!empty($data['is_default'])) {
                $customer->addresses()->update(['is_default' => false]);
            }

            $address = $customer->addresses()->create([
                ...$data,
                'is_default' => !empty($data['is_default']),
            ]);

            if ($customer->addresses()->count() === 1) {
                $address->update(['is_default' => true]);
            }
        });

        return back()->with('status', 'address-created');
    }

    private function customer(Request $request): Customer
    {
        $customer = Customer::query()
            ->where('email', $request->user()->email)
            ->where('is_active', true)
            ->firstOrFail();

        if ($customer->addresses()->doesntExist() && $customer->address) {
            $customer->addresses()->create([
                'label' => 'Alamat Utama',
                'recipient_name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'city' => $customer->city,
                'is_default' => true,
            ]);
        }

        return $customer;
    }
}
