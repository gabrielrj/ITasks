<?php

namespace App\Services;

use App\Events\UserSaved;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Throwable;

class UserManagerService implements Contracts\UserManagerInterface
{

    function getAll(): Collection
    {
        return User::all();
    }

    function findById(string $uuid): ?User
    {
        return User::query()->whereUuid($uuid)->first();
    }

    function create(array $payload): User
    {
        $user = new User();
        $user->name = $payload['name'];
        $user->email = $payload['email'];
        $user->password = bcrypt($payload['password']);
        $user->save();

        event(new UserSaved($user->email, "Usuário {$user->name} cadastrado com sucesso."));

        return $user;
    }

    function update(User $user, array $payload): bool
    {
        if($payload['password'] == null)
            $payload = Arr::except($payload, 'password');
        else
            Arr::set($payload, 'password', bcrypt($payload['password']));

        $updated = $user->update($payload);

        event(new UserSaved($user->email, "Usuário {$user->name} alterado com sucesso."));

        return $updated;
    }

    /**
     * @throws Throwable
     */
    function delete(string $uuid): bool
    {
        $user = $this->findById($uuid);

        $userName = $user->name;

        $userEmail = $user->email;

        throw_if(!$user, new \Exception('User not found!'));

        $deleted = $user->delete();

        event(new UserSaved($userEmail, "Usuário {$user->name} excluído com sucesso."));
    }
}
