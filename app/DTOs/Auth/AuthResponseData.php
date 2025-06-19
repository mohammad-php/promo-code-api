<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use App\Enums\Auth\TokenType;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * AuthData DTO.
 */
#[MapName(SnakeCaseMapper::class)]
final class AuthResponseData extends Data
{
    /**
     * Init. DTO with attributes.
     *
     * @param  string  $access_token
     * @param  TokenType  $token_type
     * @param  UserData  $user
     */
    public function __construct(
        #[Required]
        public readonly string $access_token,
        #[Required]
        public readonly TokenType $token_type,
        #[Required]
        public readonly UserData $user,
    ) {}

    /**
     * @param  $request
     *
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {

        return response()->json($this);
    }
}
