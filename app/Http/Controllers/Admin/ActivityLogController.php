<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::query()
            ->with('user:id,name,role')
            ->latest();

        $action = $request->string('action')->trim()->toString();
        $userId = $request->integer('user_id');

        if ($action !== '') {
            $query->where('action', $action);
        }

        if ($userId > 0) {
            $query->where('user_id', $userId);
        }

        $logs = $query->paginate(15)->withQueryString();

        $actions = ActivityLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $users = User::query()
            ->select('id', 'name', 'role')
            ->orderBy('name')
            ->get();

        return view('admin.activity-logs.index', compact(
            'logs',
            'actions',
            'users',
            'action',
            'userId',
        ));
    }
}
