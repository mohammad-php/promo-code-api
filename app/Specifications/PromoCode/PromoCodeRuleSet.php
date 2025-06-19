<?php

declare(strict_types=1);

namespace App\Specifications\PromoCode;

use App\Specifications\PromoCode\Composites\AndPromoCodeSpecification;
use App\Specifications\PromoCode\Rules\HasRemainingGlobalUsages;
use App\Specifications\PromoCode\Rules\HasRemainingUserUsages;
use App\Specifications\PromoCode\Rules\NotExpiredPromoCode;
use App\Specifications\PromoCode\Rules\UserIsEligibleForPromo;

final class PromoCodeRuleSet extends AndPromoCodeSpecification
{
    public function __construct()
    {
        parent::__construct([
            new UserIsEligibleForPromo(),
            new NotExpiredPromoCode(),
            new HasRemainingGlobalUsages(),
            new HasRemainingUserUsages(),
        ]);
    }
}
