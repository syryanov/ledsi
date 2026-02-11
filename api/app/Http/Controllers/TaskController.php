<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            throw new \Illuminate\Auth\AuthenticationException('USER_NOT_FOUND');
        }

        $tasks = $request->user()->tasks()->get();

        return response()->json($tasks);
    }

    public function show(Request $request)
    {
        $task = Task::find($request->id);

        if ($task->user_id !== auth()->id()) {
            throw new \Illuminate\Auth\AuthenticationException('NOT_BELONG_TO_USER');
        }

        return response()->json($task);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $task = new Task;
        $task->user_id = auth()->id();
        $task->title = $data['title'];
        $task->status = 'new';
        $task->save();

        self::cacheFlush($request->user()->id);

        return response()->json($task);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:new,in_progress,done'],
        ]);

        $task = Task::find($request->id);

        if ($task->user_id !== auth()->id()) {
            throw new \Illuminate\Auth\AuthenticationException('NOT_BELONG_TO_USER');
        }

        $task->title = $data['title'];
        $task->status = $data['status'];
        $task->save();

        self::cacheFlush($request->user()->id);

        return response()->json($task);
    }

    public function delete(Request $request)
    {
        $task = Task::find($request->id);

        if ($task->user_id !== auth()->id()) {
            throw new \Illuminate\Auth\AuthenticationException('NOT_BELONG_TO_USER');
        }

        $task->delete();

        self::cacheFlush($request->user()->id);

        return response()->noContent();
    }

    private function cacheFlush($userId)
    {
        Cache::tags(['stats', 'user_' . $userId])->flush();
        Cache::tags(['stats', 'user_admin'])->flush();
    }
}