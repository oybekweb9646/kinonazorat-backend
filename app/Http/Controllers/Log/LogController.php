<?php

namespace App\Http\Controllers\Log;

use App\Core\Helpers\Responses\Response;
use App\Models\ActivityLog;
use App\Models\ErrorLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LogController extends Controller
{
    public function activityFilter(Request $request): JsonResponse
    {
        $query = ActivityLog::query()->orderByDesc('id');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('method')) {
            $query->where('method', strtoupper($request->method_filter ?? $request->method));
        }

        if ($request->filled('method_filter')) {
            $query->where('method', strtoupper($request->method_filter));
        }

        if ($request->filled('url')) {
            $query->where('url', 'ilike', '%' . $request->url . '%');
        }

        if ($request->filled('action')) {
            $query->where('action', 'ilike', '%' . $request->action . '%');
        }

        if ($request->filled('status_code')) {
            $query->where('status_code', $request->status_code);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $size = $request->query('size', 20);
        return response()->json($query->paginate($size));
    }

    public function errorFilter(Request $request): JsonResponse
    {
        $query = ErrorLog::query()->orderByDesc('id');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('url')) {
            $query->where('url', 'ilike', '%' . $request->url . '%');
        }

        if ($request->filled('message')) {
            $query->where('message', 'ilike', '%' . $request->message . '%');
        }

        if ($request->filled('status_code')) {
            $query->where('status_code', $request->status_code);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $size = $request->query('size', 20);
        return response()->json($query->paginate($size));
    }

    public function activityStats(): JsonResponse
    {
        $stats = [
            'total'         => ActivityLog::count(),
            'today'         => ActivityLog::whereDate('created_at', today())->count(),
            'errors_today'  => ErrorLog::whereDate('created_at', today())->count(),
            'total_errors'  => ErrorLog::count(),
        ];

        return Response::success('Stats', $stats);
    }
}