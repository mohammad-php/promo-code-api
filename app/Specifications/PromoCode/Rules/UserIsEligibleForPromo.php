<?php

declare(strict_types=1);

namespace App\Specifications\PromoCode\Rules;

use App\Models\PromoCode;
use App\Models\User;
use App\Specifications\Contracts\PromoCodeSpecificationInterface;
use App\Specifications\Support\LogsSpecificationFailure;

final class UserIsEligibleForPromo extends LogsSpecificationFailure implements PromoCodeSpecificationInterface
{
    /**
     * @param  PromoCode  $promo
     * @param  User  $user
     *
     * @return bool
     */
    public function isSatisfiedBy(PromoCode $promo, User $user): bool
    {
        if ( ! $promo->users()->exists()) {
            return true;
        }

        $eligible = $promo->users->contains($user->id);

        if ( ! $eligible) {
            $this->logFailure('User is not eligible for this promo code.', [
                'user_id' => $user->id,
                'promo_code_id' => $promo->id,
            ]);
        }

        return $eligible;
    }
}
