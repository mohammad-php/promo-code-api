<?php

declare(strict_types=1);

namespace App\DTOs\PromoCode;

use App\DTOs\Auth\UserData;
use App\Enums\PromoCode\PromoCodeType;
use App\Models\PromoCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * CreatePromoCodeData DTO.
 */
#[MapName(SnakeCaseMapper::class)]
final class CreatePromoCodeData extends Data
{
    /**
     * Init. DTO with attributes.
     *
     * @param  string|null  $code
     * @param  PromoCodeType  $type
     * @param  float  $value
     * @param  Carbon|null  $expiresAt
     * @param  int|null  $maxUses
     * @param  int|null  $maxUsesPerUser
     * @param  Optional|Lazy|DataCollection|Collection  $users
     */
    public function __construct(
        #[Rule(['nullable', 'string', 'max:255', 'unique:promo_codes,code'])]
        public readonly ?string $code,
        #[Rule(['required'])]
        public readonly PromoCodeType $type,
        #[Rule(['required', 'numeric', 'gt:0'])]
        public readonly float $value,
        #[Rule(['nullable', 'date', 'after_or_equal:today'])]
        public readonly ?Carbon $expiresAt,
        #[Rule(['nullable', 'integer', 'min:1'])]
        public readonly ?int $maxUses,
        #[Rule(['nullable', 'integer', 'min:1'])]
        public readonly ?int $maxUsesPerUser,
        #[DataCollectionOf(UserData::class)]
        public readonly Optional|Lazy|DataCollection|Collection $users,
    ) {}

    /**
     * @param  Request  $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        return self::from([
            ...$request->all(),
            'users' => $request->collect('user_ids') ? UserData::collect(
                User::whereIn('id', $request->collect('user_ids'))->get()
            ) : null,
        ]);
    }

    /**
     * @param  PromoCode  $promoCode
     *
     * @return self
     */
    public static function fromModel(PromoCode $promoCode): self
    {
        return self::from([
            ...$promoCode->toArray(),
            'users' => Lazy::whenLoaded(
                'users',
                $promoCode,
                fn () => UserData::collect($promoCode->users, DataCollection::class)->withoutWrapping()
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
            'user_ids' => [
                'nullable',
                'array',
            ],
            'user_ids.*' => [
                'nullable',
                'integer',
                'distinct',
                \Illuminate\Validation\Rule::exists('users', 'id'),
            ],
        ];
    }
}
