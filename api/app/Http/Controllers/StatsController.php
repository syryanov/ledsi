<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $userFilterId = $request->input('user_id');

        if ($user->role == 'admin') {
            $cacheKey = "stats_user_admin";

            return Cache::tags(['stats', 'user_admin'])
            ->remember($cacheKey, 60, function () use ($userFilterId) {
                $query = Task::query();

                if ($userFilterId) {
                    $query->where('user_id', $userFilterId);
                }

                $total = $query->count();

                $byStatus = Task::select('status', DB::raw('count(*) as count'));

                if ($userFilterId) {
                    $byStatus = $byStatus->where('user_id', $userFilterId);
                }
                $byStatus = $byStatus->groupBy('status')->pluck('count', 'status');

                return [
                    'total' => $total,
                    'new' => $byStatus['new'] ?? 0,
                    'in_progress' => $byStatus['in_progress'] ?? 0,
                    'done' => $byStatus['done'] ?? 0,
                ];
            });
        } else {
            $userId = auth()->id();

            $cacheKey = "stats_user_{$userId}";

            return Cache::tags(['stats', 'user_'.$userId])
            ->remember($cacheKey, 60, function () use ($userId) {
                $query = Task::where('user_id', $userId);

                $total = $query->count();

                $byStatus = Task::select('status', DB::raw('count(*) as count'))
                    ->where('user_id', $userId)
                    ->groupBy('status')
                    ->pluck('count', 'status');

                return [
                    'total' => $total,
                    'new' => $byStatus['new'] ?? 0,
                    'in_progress' => $byStatus['in_progress'] ?? 0,
                    'done' => $byStatus['done'] ?? 0,
                ];
            });
        }
    }
}