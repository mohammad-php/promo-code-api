<?php

declare(strict_types=1);

namespace App\Services\PromoCode;

use App\Enums\PromoCode\PromoCodePrefix;
use App\Models\PromoCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class PromoCodeGeneratorService
{
    /**
     * Generate a unique promo code using a defined prefix.
     */
    public function generate(
        PromoCodePrefix $prefix = PromoCodePrefix::Promo
    ): string {
        $nextNumber = $this->getNextIncrementalNumber($prefix);

        return $this->composeCode($prefix, $nextNumber);
    }

    /**
     *  Retrieve the next promo code number scoped by prefix.
     *
     * @param  PromoCodePrefix  $prefix
     *
     * @return int
     */
    private function getNextIncrementalNumber(PromoCodePrefix $prefix): int
    {
        return DB::transaction(function () use ($prefix) {
            $lastCode = PromoCode::query()
                ->sharedLock()
                ->where('code', 'like', "{$prefix->value}-%")
                ->latest('id')
                ->value('code');

            return filled($lastCode)
                ? (int) Str::afterLast($lastCode, '-') + 1
                : 1;
        });
    }

    /**
     * Format the final promo code as a string.
     */
    private function composeCode(PromoCodePrefix $prefix, int $number): string
    {
        return Str::of($prefix->value)
            ->append('-')
            ->append(Str::padLeft((string) $number, 5, '0'))
            ->toString();
    }
}
