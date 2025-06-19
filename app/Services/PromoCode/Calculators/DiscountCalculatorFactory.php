<?php

declare(strict_types=1);

namespace App\Services\PromoCode\Calculators;

use App\Enums\PromoCode\PromoCodeType;
use App\Models\PromoCode;

final class DiscountCalculatorFactory
{
    /**
     * @param  PromoCode  $promo
     *
     * @return DiscountCalculatorInterface
     */
    public function make(PromoCode $promo): DiscountCalculatorInterface
    {
        return match ($promo->type) {
            PromoCodeType::Percentage => new PercentageDiscountCalculator(),
            PromoCodeType::Value => new ValueDiscountCalculator(),
        };
    }
}
