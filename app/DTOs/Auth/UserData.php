<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use App\DTOs\EntityData;
use App\DTOs\ValidMobile;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[MapName(SnakeCaseMapper::class)]
final class UserData extends Data
{
    /**
     * @param  int|null  $id
     * @param  string  $name
     * @param  string  $email
     */
    public function __construct(
        #[Rule('integer|exists:users,id')]
        public readonly ?int $id,
        #[Rule('required|string|max:255')]
        public readonly string $name,
        #[Rule(['required', 'email', 'max:255'])]
        public readonly string $email,
    ) {}

    /**
     * @param  User  $user
     *
     * @return self
     */
    public static function fromModel(User $user): self
    {
        return self::from([
            ...$user->toArray(),
            'entity' => Lazy::whenLoaded(
                'entity',
                $user,
                static fn () => EntityData::from($user->entity()->first())
            ),
        ]);
    }

    /**
     * @param  ValidationContext  $context
     *
     * @return array|array[]|string[]
     */
    public static function rules(ValidationContext $context): array
    {
        return [
            'mobile' => [
                'required',
                'numeric',
                'regex:/^[0-9]+$/',
                'max_digits:12',
                'required_with:country_code',
                Rule::unique('users')->where(
                    fn ($query) => $query->where('country_code', request('country_code'))
                ),
                new ValidMobile(),
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }
}
