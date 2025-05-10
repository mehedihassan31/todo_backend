<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Exception;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService)
    {
    }

    public function index(Request $request): ResourceCollection|JsonResponse
    {
        try {
            $tasks = $this->taskService->get($request->keyword);
            return TaskResource::collection($tasks);
        } catch (Exception $e) {
            return ApiResponse::error('Failed to fetch tasks.'.$e->getMessage());
        }
    }

    public function store(TaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->store($request->validated(), $request->user());
            return ApiResponse::success('Task created successfully.', new TaskResource($task), 201);
        } catch (Exception $e) {
            return ApiResponse::error('Failed to create task.'.$e->getMessage());
        }
    }

    public function show(Task $task): JsonResponse
    {
        try {
            return ApiResponse::success('Task retrieved successfully.', new TaskResource($task));
        } catch (Exception $e) {
            return ApiResponse::error('Failed to retrieve task.');
        }
    }

    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        try {
            $this->taskService->update($request->validated(), $task);
            return ApiResponse::success('Task updated successfully.', new TaskResource($task));
        } catch (Exception $e) {
            return ApiResponse::error('Failed to update task.');
        }
    }

    public function destroy(Task $task): JsonResponse
    {
        try {
            $this->taskService->destroy($task);
            return ApiResponse::success('Task deleted successfully.');
        } catch (Exception $e) {
            return ApiResponse::error('Failed to delete task.');
        }
    }

    public function complete(Task $task): JsonResponse
    {
        try {
            $this->taskService->toggle($task);
            return ApiResponse::success('Task marked as completed.', new TaskResource($task));
        } catch (Exception $e) {
            return ApiResponse::error('Failed to mark task as completed.');
        }
    }
}
