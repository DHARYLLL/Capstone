<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(session('user_role') === 'Administrator', 403);

        $filter = $request->string('filter')->lower()->value();
        $kbLogs = ActivityLog::query()->where('type', 'kb');
        $totalUploads = (clone $kbLogs)->count();
        $indexedToday = (clone $kbLogs)->where('event', 'Indexed')->whereDate('created_at', today())->count();
        $failedCount = (clone $kbLogs)->where(function ($query): void {
            $query->where('status', 'Failed')->orWhere('event', 'like', '%failed%')->orWhere('event', 'Rejected');
        })->count();
        $pendingCount = (clone $kbLogs)->where(function ($query): void {
            $query->where('status', 'Pending')->orWhere('event', 'Staged');
        })->count();
        $logs = ActivityLog::query()
            ->with('user')
            ->filterByStatus($filter)
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('admin.logs', compact('logs', 'totalUploads', 'indexedToday', 'failedCount', 'pendingCount', 'filter'));
    }
}