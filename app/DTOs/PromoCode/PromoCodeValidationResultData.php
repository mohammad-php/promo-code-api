<?php

declare(strict_types=1);

namespace App\DTOs\PromoCode;

use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * CreatePromoCodeData DTO.
 */
#[MapName(SnakeCaseMapper::class)]
final class PromoCodeValidationResultData extends Data
{
    /**
     * Init. DTO with attributes.
     *
     * @param  float  $price
     * @param  float  $discountedAmount
     * @param  float  $finalPrice
     */
    public function __construct(
        #[Rule(['required', 'numeric', 'min:0.01'])]
        public readonly float $price,
        #[Rule(['required', 'numeric', 'min:0.01'])]
        public readonly float $discountedAmount,
        #[Rule(['required', 'numeric', 'min:0.01'])]
        public readonly float $finalPrice,
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
