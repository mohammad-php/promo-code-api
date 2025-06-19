<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\DTOs\Auth\AuthResponseData;
use App\DTOs\Auth\LoginData;
use App\DTOs\Auth\UserData;
use App\Enums\Auth\TokenType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class LoginService
{
    /**
     * @param User $user
     * @param LoginData $data
     *
     * @return AuthResponseData
     * @throws ValidationException
     */
    public function login(
        User $user,
        LoginData $data
    ): AuthResponseData {
        $this->validatePassword($user, $data->password);

        $user->tokens()->delete();

        $token = $user->createToken($user->username . '-api_token')->plainTextToken;

        return new AuthResponseData(
            access_token: $token,
            token_type: TokenType::Bearer,
            user: UserData::from($user)
        );
    }

    /**
     * Validate the user's password.
     *
     * @param  User  $user
     * @param  string  $password
     *
     * @throws ValidationException
     */
    private function validatePassword(
        User $user,
        string $password
    ): void {
        if ( ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => __('auth.invalid_credentials'),
            ]);
        }
    }
}
