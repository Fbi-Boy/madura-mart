<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistributorStoreRequest;
use App\Http\Requests\DistributorUpdateRequest;
use App\Models\Distributor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DistributorController extends Controller
{
    public function index(): View
    {
        $distributors = Distributor::query()->orderBy('name')->paginate(10);

        return view('admin.distributors.index', compact('distributors'));
    }

    public function create(): View
    {
        return view('admin.distributors.create');
    }

    public function store(DistributorStoreRequest $request): RedirectResponse
    {
        Distributor::create($request->validated() + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('admin.distributors.index')
            ->with('success', 'Distributor berhasil ditambahkan.');
    }

    public function edit(Distributor $distributor): View
    {
        return view('admin.distributors.edit', compact('distributor'));
    }

    public function update(DistributorUpdateRequest $request, Distributor $distributor): RedirectResponse
    {
        $distributor->update($request->validated() + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('admin.distributors.index')
            ->with('success', 'Distributor berhasil diperbarui.');
    }

    public function destroy(Distributor $distributor): RedirectResponse
    {
        $distributor->delete();

        return to_route('admin.distributors.index')
            ->with('success', 'Distributor berhasil dihapus.');
    }
}