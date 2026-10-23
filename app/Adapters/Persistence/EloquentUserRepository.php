<?php

namespace App\Adapters\Persistence;

use App\Application\Ports\UserRepositoryInterface;
use App\Domain\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByUsername(string $username): ?User
    {
        $model = UserEloquentModel::where('username', strtolower(trim($username)))->first();

        if (!$model) {
            return null;
        }

        return new User(
            $model->id,
            $model->username,
            $model->password_hash,
            $model->role
        );
    }

    public function save(User $user): void
    {
        UserEloquentModel::updateOrCreate(
            ['id' => $user->id()],
            [
                'username' => $user->username(),
                'password_hash' => $user->passwordHash(),
                'role' => $user->role(),
            ]
        );
    }
}