<?php

declare(strict_types=1);

namespace App\Specifications\PromoCode\Composites;

use App\Models\PromoCode;
use App\Models\User;
use App\Specifications\Contracts\PromoCodeSpecificationInterface;

class AndPromoCodeSpecification implements PromoCodeSpecificationInterface
{
    /**
     * @param  PromoCodeSpecificationInterface[]  $rules
     */
    public function __construct(
        private readonly array $rules,
    ) {}

    /**
     * @param  PromoCode  $promo
     * @param  User  $user
     *
     * @return bool
     */
    public function isSatisfiedBy(PromoCode $promo, User $user): bool
    {
        foreach ($this->rules as $rule) {
            if ( ! $rule->isSatisfiedBy($promo, $user)) {
                return false;
            }
        }

        return true;
    }
}
