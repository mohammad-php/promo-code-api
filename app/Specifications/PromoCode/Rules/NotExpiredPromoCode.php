<?php

declare(strict_types=1);

namespace App\Specifications\PromoCode\Rules;

use App\Models\PromoCode;
use App\Models\User;
use App\Specifications\Contracts\PromoCodeSpecificationInterface;

final class NotExpiredPromoCode implements PromoCodeSpecificationInterface
{
    /**
     * @param  PromoCode  $promo
     * @param  User  $user
     *
     * @return bool
     */
    public function isSatisfiedBy(PromoCode $promo, User $user): bool
    {
        return null === $promo->expires_at || now()->lessThan($promo->expires_at);
    }
}
