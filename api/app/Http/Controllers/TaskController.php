<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskPostAndPutRequest;
use App\Services\Contracts\TaskManagerInterface;
use App\Services\Contracts\UserManagerInterface;
use Illuminate\Support\Arr;

class TaskController extends Controller
{
    use CallableIntercept;

    protected TaskManagerInterface $taskManagerService;
    protected UserManagerInterface $userManagerService;

    public function __construct(TaskManagerInterface $taskManagerService, UserManagerInterface $userManagerService)
    {
        $this->taskManagerService = $taskManagerService;
        $this->userManagerService = $userManagerService;
    }

    public function getAllTasks(): \Illuminate\Http\JsonResponse
    {
        return $this->run(function () {
            return[
                'tasks' => $this->taskManagerService->getAll()
            ];
        });
    }

    public function getAllTasksByUser($userId): \Illuminate\Http\JsonResponse
    {
        return $this->run(function () use($userId){
            $user = $this->userManagerService->findById($userId);

            return [
                'tasks' => $this->taskManagerService->getAllByUserId($user->id)
            ];
        });
    }

    public function findTaskById($id): \Illuminate\Http\JsonResponse
    {
        return $this->run(function () use($id){
            return [
                'task' => $this->taskManagerService->findById($id)
            ];
        });
    }

    public function createNewTask(TaskPostAndPutRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->run(function () use($request){
            $user = $this->userManagerService->findById($request->users_id);

            $payload = $request->only('name', 'description', 'status');

            $payload = Arr::add($payload, 'users_id', $user->id);

            return [
                'task' => $this->taskManagerService->create($payload)
            ];
        });
    }

    public function updateTask(TaskPostAndPutRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        return $this->run(function () use($request, $id){
            $user = $this->userManagerService->findById($request->users_id);

            $task = $this->taskManagerService->findById($request->id);

            $payload = $request->only('name', 'description', 'status');

            $payload = Arr::add($payload, 'users_id', $user->id);

            return [
                'updated' => $this->taskManagerService->update($task, $payload)
            ];
        });
    }

    public function deleteTask($id): \Illuminate\Http\JsonResponse
    {
        return $this->run(function () use($id){
            return [
                'deleted' => $this->taskManagerService->delete($id)
            ];
        });
    }
}
