<?php

namespace AppHttpControllersAdmin;

use AppHttpControllersController;
use AppHttpRequestsCustomerStoreRequest;
use AppHttpRequestsCustomerUpdateRequest;
use AppModelsCustomer;
use IlluminateHttpRedirectResponse;
use IlluminateViewView;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::query()
            ->orderBy('name')
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('admin.customers.create');
    }

    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        Customer::create($request->validated() + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('admin.customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(CustomerUpdateRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated() + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('admin.customers.index')
            ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return to_route('admin.customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}