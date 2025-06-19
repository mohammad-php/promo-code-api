<?php

declare(strict_types=1);

namespace App\Http\Api\V1\Controllers\Auth;

use App\DTOs\Auth\AuthResponseData;
use App\DTOs\Auth\LoginData;
use App\Http\Api\V1\Controllers\Controller;
use App\Services\Auth\LoginService;
use App\Services\Auth\UserService;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 */
final class LoginController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly LoginService $loginService,
    ) {}

    /**
     * Login By Email
     *
     * @unauthenticated
     *
     * @param  LoginData  $data
     *
     * @throws ValidationException
     * @return AuthResponseData
     *
     * @bodyParam email string required
     * Email <br>
     * Example: admin@promo.com
     *
     * @bodyParam password string required
     * Password <br>
     * Must be minimum 8 characters <br>
     * Example: password
     *
     */
    public function __invoke(
        LoginData $data,
    ): AuthResponseData {
        $user = $this->userService->findByEmailOrFail(
            $data->email,
        );

        return $this->loginService->login($user, $data);
    }
}
