<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeliveryStatusController extends Controller
{
    public function update(Request $request, Order $order): RedirectResponse
    {
        $courier = Courier::query()
            ->where('is_active', true)
            ->where('email', $request->user()->email)
            ->first();

        abort_unless($courier && $order->courier_id === $courier->id, 403);

        $data = $request->validate([
            'status' => ['required', 'in:processing,shipped,delivered'],
        ]);

        $nextStatuses = [
            'pending' => 'processing',
            'processing' => 'shipped',
            'shipped' => 'delivered',
        ];

        abort_unless(
            ($nextStatuses[$order->status] ?? null) === $data['status'],
            422,
            'Status pengiriman harus mengikuti urutan proses.'
        );

        $order->update(['status' => $data['status']]);

        return to_route('dashboard')->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
