<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Throwable;

class SystemMonitoringController extends Controller
{
    public function index(): View
    {
        $checks = [
            'database' => $this->check('Database', function (): string {
                DB::select('select 1');

                return 'Koneksi database aktif';
            }),
            'cache' => $this->check('Cache', function (): string {
                $key = 'system-monitoring:health-check';
                Cache::put($key, 'ok', 10);
                $healthy = Cache::get($key) === 'ok';
                Cache::forget($key);

                if (! $healthy) {
                    throw new \RuntimeException('Cache read/write gagal.');
                }

                return 'Read/write cache normal';
            }),
            'storage' => $this->check('Storage', function (): string {
                if (! is_writable(storage_path())) {
                    throw new \RuntimeException('Direktori storage tidak writable.');
                }

                Storage::disk('local')->exists('.');

                return 'Direktori storage dapat digunakan';
            }),
        ];

        $checks['application'] = [
            'label' => 'Application',
            'status' => 'ok',
            'message' => 'Laravel '.app()->version().' · '.config('app.env'),
        ];

        $healthyChecks = collect($checks)->where('status', 'ok')->count();
        $recentActivities = ActivityLog::query()
            ->with('user:id,name,role')
            ->latest()
            ->limit(8)
            ->get(['id', 'user_id', 'action', 'description', 'created_at']);

        $todayActivityCount = ActivityLog::query()
            ->whereDate('created_at', Carbon::today())
            ->count();

        return view('admin.system-monitoring.index', compact(
            'checks',
            'healthyChecks',
            'recentActivities',
            'todayActivityCount',
        ));
    }

    private function check(string $label, callable $callback): array
    {
        try {
            return [
                'label' => $label,
                'status' => 'ok',
                'message' => $callback(),
            ];
        } catch (Throwable $exception) {
            report($exception);

            return [
                'label' => $label,
                'status' => 'error',
                'message' => $exception->getMessage(),
            ];
        }
    }
}
