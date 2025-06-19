<?php

declare(strict_types=1);

namespace App\Services\PromoCode;

use App\DTOs\PromoCode\PromoCodeValidationResultData;
use App\Enums\PromoCode\PromoCodeType;
use App\Models\PromoCode;
use App\Models\User;
use App\Services\PromoCode\Calculators\DiscountCalculatorFactory;
use App\Specifications\PromoCode\PromoCodeRuleSet;
use Illuminate\Contracts\Auth\Authenticatable;

final class PromoCodeValidationService
{
    public function __construct(
        private readonly DiscountCalculatorFactory $calculatorFactory,
    ) {}

    /**
     * @param  PromoCode  $promo
     * @param  User|Authenticatable  $user
     * @param  float  $price
     *
     * @return PromoCodeValidationResultData
     */
    public function validate(
        PromoCode $promo,
        User|Authenticatable $user,
        float $price
    ): PromoCodeValidationResultData {
        $specs = new PromoCodeRuleSet();

        if ( ! $specs->isSatisfiedBy($promo, $user)) {
            abort(404, __('messages.invalid_promo_code'));
        }

        $discount = $this->calculatorFactory->make($promo)->calculate($promo, $price);

        $promo->usages()->create([
            'user_id' => $user->id,
            'used_at' => now(),
        ]);

        return new PromoCodeValidationResultData(
            price: $price,
            discountedAmount: $discount,
            finalPrice: max($price - $discount, 0),
        );
    }

    /**
     * @param  PromoCode  $promo
     * @param  float  $price
     *
     * @return float
     */
    private function calculateDiscount(
        PromoCode $promo,
        float $price
    ): float {
        return match ($promo->type) {
            PromoCodeType::Percentage => round($price * ($promo->value / 100), 2),
            PromoCodeType::Value => (float) min($promo->value, $price),
        };
    }
}
