<?php

declare(strict_types=1);

namespace App\Specifications\Contracts;

use App\Models\PromoCode;
use App\Models\User;

interface PromoCodeSpecificationInterface
{
    /**
     * @param PromoCode $promo
     * @param User $user
     *
     * @return bool
     */
    public function isSatisfiedBy(PromoCode $promo, User $user): bool;
}
