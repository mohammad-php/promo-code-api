<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use Illuminate\Validation\Rules\Password;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * AuthData DTO.
 */
#[MapName(SnakeCaseMapper::class)]
final class LoginData extends Data
{
    /**
     * Init. DTO with attributes.
     *
     * @param  string  $email
     * @param  string|null  $password
     */
    public function __construct(
        #[Rule(['required', 'email', 'max:255'])]
        public readonly string $email,
        public readonly ?string $password,
    ) {}

    /**
     * @param  ValidationContext  $context
     *
     * @return array|array[]|string[]
     */
    public static function rules(ValidationContext $context): array
    {
        return [
            'password' => [
                'required',
                Password::min(8),
            ],
        ];
    }
}
