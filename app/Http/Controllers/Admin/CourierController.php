<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourierStoreRequest;
use App\Http\Requests\CourierUpdateRequest;
use App\Models\Courier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourierController extends Controller
{
    public function index(): View
    {
        $couriers = Courier::query()->orderBy('name')->paginate(10);

        return view('admin.couriers.index', compact('couriers'));
    }

    public function create(): View
    {
        return view('admin.couriers.create');
    }

    public function store(CourierStoreRequest $request): RedirectResponse
    {
        Courier::create($request->validated() + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('admin.couriers.index')
            ->with('success', 'Kurir berhasil ditambahkan.');
    }

    public function edit(Courier $courier): View
    {
        return view('admin.couriers.edit', compact('courier'));
    }

    public function update(CourierUpdateRequest $request, Courier $courier): RedirectResponse
    {
        $courier->update($request->validated() + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('admin.couriers.index')
            ->with('success', 'Kurir berhasil diperbarui.');
    }

    public function destroy(Courier $courier): RedirectResponse
    {
        $courier->delete();

        return to_route('admin.couriers.index')
            ->with('success', 'Kurir berhasil dihapus.');
    }
}