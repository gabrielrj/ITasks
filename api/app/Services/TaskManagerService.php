<?php

namespace App\Services;

use App\Events\TaskSaved;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class TaskManagerService implements Contracts\TaskManagerInterface
{

    function getAll(): Collection
    {
        return Task::query()->with('user')->orderByDesc('created_at')->get();
    }

    function getAllByUserId(int $userId): Collection
    {
        return Task::query()
            ->with('user')
            ->has('user')
            ->whereUsersId($userId)
            ->orderByDesc('created_at')
            ->get();
    }

    function findById(string $uuid): ?Task
    {
        return Task::query()->with('user')->whereUuid($uuid)->first();
    }

    function create(array $payload): Task
    {
        $task = new Task();

        $task->name = $payload['name'];
        $task->description = $payload['description'];
        $task->status = $payload['status'];
        $task->users_id = $payload['users_id'];
        $task->save();

        event(new TaskSaved($task->user, "A tarefa {$task->name} foi cadastrada para você."));

        return $task;
    }

    function update(Task $task, array $payload): bool
    {
        if($task->status != 'completed' && $payload['status'] === 'completed')
            $payload = Arr::add($payload, 'completed_datetime', now()->format('Y-m-d H:i:s'));

        $updated = $task->update($payload);

        $userLogged = auth()->user();

        event(new TaskSaved($task->user, "A sua tarefa de '{$task->name}' foi alterada pelo usuário {$userLogged->name}."));

        return $updated;
    }

    /**
     * @throws \Throwable
     */
    function delete(string $uuid): bool
    {
        $task = $this->findById($uuid);

        $taskName = $task->name;

        throw_if(!$task, new \Exception('Task not found!'));

        $deleted = $task->delete();

        $userLogged = auth()->user();

        event(new TaskSaved($task->user, "A tarefa {$taskName} que estava na sua lista foi excluída por {$userLogged->name}."));

        return $deleted;
    }
}
