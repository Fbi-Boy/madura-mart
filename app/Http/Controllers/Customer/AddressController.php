<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
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

        return to_route('customer.address.edit')->with('status', 'address-created');
    }

    public function setDefault(Request $request, CustomerAddress $address): RedirectResponse
    {
        $customer = $this->customer($request);
        abort_unless($address->customer_id === $customer->id, 403);

        DB::transaction(function () use ($customer, $address): void {
            $customer->addresses()->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        });

        return back()->with('status', 'address-default-updated');
    }

    public function destroy(Request $request, CustomerAddress $address): RedirectResponse
    {
        $customer = $this->customer($request);
        abort_unless($address->customer_id === $customer->id, 403);

        DB::transaction(function () use ($customer, $address): void {
            $wasDefault = $address->is_default;
            $address->delete();

            if ($wasDefault) {
                $replacement = $customer->addresses()->latest('id')->first();
                $replacement?->update(['is_default' => true]);
            }
        });

        return back()->with('status', 'address-deleted');
    }

    private function customer(Request $request): Customer
    {
        return Customer::query()
            ->where('email', $request->user()->email)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
