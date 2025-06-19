<?php

declare(strict_types=1);

namespace App\Support\Traits;

trait EnumTrait
{
    /**
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
