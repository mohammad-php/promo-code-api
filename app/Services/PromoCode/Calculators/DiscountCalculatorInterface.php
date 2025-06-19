<?php

declare(strict_types=1);

namespace App\Services\PromoCode\Calculators;

use App\Models\PromoCode;

interface DiscountCalculatorInterface
{
    public function calculate(PromoCode $promo, float $price): float;
}
