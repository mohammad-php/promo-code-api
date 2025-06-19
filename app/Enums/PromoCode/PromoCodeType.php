<?php

declare(strict_types=1);

namespace App\Enums\PromoCode;

use App\Support\Traits\EnumTrait;

enum PromoCodeType: string
{
    use EnumTrait;
    case Percentage = 'percentage';
    case Value = 'value';
}
