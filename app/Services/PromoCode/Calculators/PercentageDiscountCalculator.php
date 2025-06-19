<?php

declare(strict_types=1);

namespace App\Services\PromoCode\Calculators;

use App\Models\PromoCode;

final class PercentageDiscountCalculator implements DiscountCalculatorInterface
{
    /**
     * @param  PromoCode  $promo
     * @param  float  $price
     *
     * @return float
     */
    public function calculate(PromoCode $promo, float $price): float
    {
        return round($price * ($promo->value / 100), 2);
    }
}
