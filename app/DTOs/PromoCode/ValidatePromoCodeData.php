<?php

declare(strict_types=1);

namespace App\DTOs\PromoCode;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * CreatePromoCodeData DTO.
 */
#[MapName(SnakeCaseMapper::class)]
final class ValidatePromoCodeData extends Data
{
    /**
     * Init. DTO with attributes.
     *
     * @param  string  $code
     * @param  float  $price
     */
    public function __construct(
        #[Rule(['required', 'string', 'max:255', 'exists:promo_codes,code'])]
        public readonly string $code,
        #[Rule(['required', 'numeric', 'gt:1'])]
        public readonly float $price,
    ) {
    }
}
