<?php

namespace App\Services\Contracts;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskManagerInterface
{
    function getAll() : Collection;

    function getAllByUserId(int $userId) : Collection;

    function findById(string $uuid) : ?Task;

    function create(array $payload) : Task;

    function update(Task $task, array $payload) : bool;

    function delete(string $uuid) : bool;
}
