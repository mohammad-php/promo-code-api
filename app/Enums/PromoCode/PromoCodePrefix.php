<?php

declare(strict_types=1);

namespace App\Enums\PromoCode;

use App\Support\Traits\EnumTrait;

enum PromoCodePrefix: string
{
    use EnumTrait;
    case Promo = 'PROMO';
}
