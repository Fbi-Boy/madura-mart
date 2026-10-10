<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use App\Models\Sale;
use App\Models\SaleReturn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashierShiftController extends Controller
{
    public function openForm(): View
    {
        $openShift = CashierShift::where('user_id', auth()->id())
            ->whereNull('closed_at')
            ->latest('opened_at')
            ->first();

        return view('kasir.buka-shift.index', compact('openShift'));
    }

    public function open(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shift_number' => ['required', 'string', 'max:50', 'unique:cashier_shifts,shift_number'],
            'shift_type'   => ['required', 'in:pagi-siang,siang-malam'],
            'opened_at'    => ['required', 'date'],
            'opening_cash' => ['required', 'numeric', 'min:0'],
        ]);

        $existing = CashierShift::where('user_id', auth()->id())
            ->whereNull('closed_at')
            ->first();

        if ($existing) {
            return back()->withErrors([
                'shift_number' => 'Anda masih memiliki shift aktif (' . $existing->shift_number . '). Tutup dulu shift sebelumnya.',
            ]);
        }

        if (!auth()->user()->branch_id) {
            return back()->withErrors([
                'shift_number' => 'Akun Anda belum ditugaskan ke cabang manapun. Hubungi admin.',
            ]);
        }

        CashierShift::create([
            'shift_number' => $validated['shift_number'],
            'user_id'      => auth()->id(),
            'branch_id'    => auth()->user()->branch_id,
            'shift_type'   => $validated['shift_type'],
            'opened_at'    => $validated['opened_at'],
            'opening_cash' => $validated['opening_cash'],
            'status'       => 'open',
        ]);

        return redirect()
            ->route('kasir.transaksi-baru')
            ->with('success', 'Shift berhasil dibuka. Selamat bekerja!');
    }

    public function closeForm(): View
    {
        $openShift = CashierShift::where('user_id', auth()->id())
            ->whereNull('closed_at')
            ->latest('opened_at')
            ->first();

        if (!$openShift) {
            return view('kasir.tutup-shift.index', [
                'openShift'    => null,
                'expectedCash' => 0,
                'summary'      => [],
            ]);
        }

        $cashSales = Sale::where('shift_id', $openShift->id)
            ->where('payment_method', 'cash')
            ->where('status', 'paid')
            ->sum('paid_amount');

        $cashRefunds = SaleReturn::where('user_id', auth()->id())
            ->where('refund_method', 'cash')
            ->whereBetween('return_date', [$openShift->opened_at, now()])
            ->sum('total');

        $totalTransactions = Sale::where('shift_id', $openShift->id)
            ->where('status', 'paid')
            ->count();

        $totalRevenue = Sale::where('shift_id', $openShift->id)
            ->where('status', 'paid')
            ->sum('total');

        $expectedCash = (float) $openShift->opening_cash
            + (float) $cashSales
            - (float) $cashRefunds;

        $summary = [
            'cash_sales'         => (float) $cashSales,
            'cash_refunds'       => (float) $cashRefunds,
            'total_revenue'      => (float) $totalRevenue,
            'total_transactions' => (int) $totalTransactions,
        ];

        return view('kasir.tutup-shift.index', compact('openShift', 'expectedCash', 'summary'));
    }

    public function close(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shift_id'     => ['required', 'exists:cashier_shifts,id'],
            'closing_cash' => ['required', 'numeric', 'min:0'],
            'notes'        => ['nullable', 'string', 'max:1000'],
        ]);

        return DB::transaction(function () use ($validated) {
            $shift = CashierShift::where('id', $validated['shift_id'])
                ->where('user_id', auth()->id())
                ->whereNull('closed_at')
                ->lockForUpdate()
                ->firstOrFail();

            $cashSales = Sale::where('shift_id', $shift->id)
                ->where('payment_method', 'cash')
                ->where('status', 'paid')
                ->sum('paid_amount');

            $cashRefunds = SaleReturn::where('user_id', auth()->id())
                ->where('refund_method', 'cash')
                ->whereBetween('return_date', [$shift->opened_at, now()])
                ->sum('total');

            $expectedCash = (float) $shift->opening_cash
                + (float) $cashSales
                - (float) $cashRefunds;

            $shift->update([
                'closed_at'      => now(),
                'closing_cash'   => $validated['closing_cash'],
                'expected_cash'  => $expectedCash,
                'closing_notes'  => $validated['notes'] ?? null,
                'status'         => 'closed',
            ]);

            return redirect()
                ->route('kasir.buka-shift')
                ->with('success', 'Shift berhasil ditutup. Terima kasih!');
        });
    }

    public function history(): View
    {
        $shifts = CashierShift::with('branch')
            ->where('user_id', auth()->id())
            ->latest('opened_at')
            ->paginate(15);

        return view('kasir.riwayat-shift.index', compact('shifts'));
    }
}
