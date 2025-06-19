<?php

declare(strict_types=1);

namespace App\Services\PromoCode\Calculators;

use App\Models\PromoCode;

final class ValueDiscountCalculator implements DiscountCalculatorInterface
{
    /**
     * @param  PromoCode  $promo
     * @param  float  $price
     *
     * @return float
     */
    public function calculate(PromoCode $promo, float $price): float
    {
        return min($promo->value, $price);
    }
}
