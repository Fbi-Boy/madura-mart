<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function edit(Request $request): View
    {
        $customer = Customer::query()
            ->where('email', $request->user()->email)
            ->where('is_active', true)
            ->firstOrFail();

        return view('customer.address.edit', compact('customer'));
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = Customer::query()
            ->where('email', $request->user()->email)
            ->where('is_active', true)
            ->firstOrFail();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
        ]);

        $customer->update($data);

        return to_route('customer.address.edit')->with('status', 'address-updated');
    }
}
