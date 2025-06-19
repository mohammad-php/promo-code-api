<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

final class UserService
{
    /**
     * @param  string  $email
     *
     * @return User
     */
    public function findByEmailOrFail(
        string $email
    ): User {
        return User::whereEmail($email)->firstOrFail();
    }
}
