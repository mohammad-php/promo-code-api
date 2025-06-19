<?php

declare(strict_types=1);

namespace App\Specifications\PromoCode\Rules;

use App\Models\PromoCode;
use App\Models\User;
use App\Specifications\Contracts\PromoCodeSpecificationInterface;

final class HasRemainingUserUsages implements PromoCodeSpecificationInterface
{
    /**
     * @param  PromoCode  $promo
     * @param  User  $user
     *
     * @return bool
     */
    public function isSatisfiedBy(PromoCode $promo, User $user): bool
    {
        if (is_null($promo->max_uses_per_user)) {
            return true;
        }
        
        return $promo->usages()->whereUserId($user->id)->count() < $promo->max_uses_per_user;
    }
}
