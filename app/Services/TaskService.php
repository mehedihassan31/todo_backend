<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function get(string $keyword = null): LengthAwarePaginator|Collection
    {
        $query = Task::where('user_id', Auth::user()->id)->search($keyword)->latest();
        return $query->get();
    }

    public function store(array $validatedData): Task
    {
        $validatedData['user_id'] = Auth::user()->id;
        return Task::create($validatedData);
    }

    public function update(array $validatedData, Task $task): bool
    {
        return $task->update($validatedData);
    }

    public function destroy(Task $task): bool
    {
        return $task->delete();
    }

    public function toggle(Task $task): void
    {
        $task->update([
            'is_completed' => !$task->is_completed,
        ]);
    }
}
