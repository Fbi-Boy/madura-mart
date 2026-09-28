<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use App\Services\ActivityLogService;
use App\Services\OrderStateMachine;
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
            'status' => ['required', 'in:processing,shipped,delivered,failed'],
            'failure_reason' => ['required_if:status,failed', 'nullable', 'string', 'max:500'],
        ]);

        $nextStatuses = [
            'pending' => ['processing', 'failed'],
            'processing' => ['shipped', 'failed'],
            'shipped' => ['delivered', 'failed'],
        ];

        abort_unless(
            in_array($data['status'], $nextStatuses[$order->status] ?? [], true),
            422,
            'Status pengiriman harus mengikuti urutan proses.'
        );

        $previousStatus = $order->status;
        OrderStateMachine::transition(
            $order,
            $data['status'],
            $request->user(),
            $data['status'] === 'failed'
                ? 'Pengiriman gagal: '.$data['failure_reason']
                : 'Status pengiriman diperbarui oleh kurir.',
        );

        $order->update([
            'delivery_failure_reason' => $data['status'] === 'failed' ? $data['failure_reason'] : null,
        ]);

        ActivityLogService::record(
            'delivery.status_updated',
            "Status pengiriman {$order->order_number} berubah dari {$previousStatus} menjadi {$data['status']}.",
            $order,
            [
                'from' => $previousStatus,
                'to' => $data['status'],
                'failure_reason' => $data['status'] === 'failed' ? $data['failure_reason'] : null,
            ],
            $request,
        );

        return to_route('dashboard')->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
