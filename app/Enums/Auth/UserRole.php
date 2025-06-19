<?php

declare(strict_types=1);

namespace App\Enums\Auth;

enum UserRole: string
{
    case Admin = 'admin';
    case User  = 'user';
}
