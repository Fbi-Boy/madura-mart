<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashierShiftController extends Controller
{
    public function openForm(): View
    {
        $openShift = CashierShift::where('user_id', auth()->id())->where('status', 'open')->first();
        return view('kasir.buka-shift.index', compact('openShift'));
    }

    public function open(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shift_number' => ['required', 'string', 'max:50', 'unique:cashier_shifts,shift_number'],
            'opened_at' => ['required', 'date'],
            'opening_cash' => ['required', 'numeric', 'min:0'],
        ]);

        if (CashierShift::where('user_id', auth()->id())->where('status', 'open')->exists()) {
            return back()->withErrors(['shift_number' => 'Anda masih memiliki shift yang aktif.'])->withInput();
        }

        CashierShift::create($validated + ['user_id' => auth()->id(), 'status' => 'open']);

        return redirect()->route('kasir.tutup-shift')->with('success', 'Shift berhasil dibuka.');
    }

    public function closeForm(): View
    {
        $shift = CashierShift::where('user_id', auth()->id())->where('status', 'open')->firstOrFail();
        $cashSales = $shift->sales()->where('status', 'paid')->where('payment_method', 'cash')->sum('total');
        $expectedCash = (float) $shift->opening_cash + (float) $cashSales;
        return view('kasir.tutup-shift.index', compact('shift', 'cashSales', 'expectedCash'));
    }

    public function close(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'closing_cash' => ['required', 'numeric', 'min:0'],
            'closing_notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $shift = CashierShift::where('user_id', auth()->id())->where('status', 'open')->lockForUpdate()->firstOrFail();
            $cashSales = $shift->sales()->where('status', 'paid')->where('payment_method', 'cash')->sum('total');

            $shift->update([
                'closed_at' => now(),
                'closing_cash' => $validated['closing_cash'],
                'expected_cash' => (float) $shift->opening_cash + (float) $cashSales,
                'closing_notes' => $validated['closing_notes'] ?? null,
                'status' => 'closed',
            ]);
        });

        return redirect()->route('kasir.riwayat-shift')->with('success', 'Shift berhasil ditutup.');
    }

    public function history(): View
    {
        $shifts = CashierShift::where('user_id', auth()->id())->latest('opened_at')->paginate(10);
        return view('kasir.riwayat-shift.index', compact('shifts'));
    }
}