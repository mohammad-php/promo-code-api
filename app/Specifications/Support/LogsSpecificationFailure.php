<?php

declare(strict_types=1);

namespace App\Specifications\Support;

use Illuminate\Support\Facades\Log;

class LogsSpecificationFailure
{
    /**
     * @param  string  $reason
     * @param  array  $context
     *
     * @return void
     */
    protected function logFailure(string $reason, array $context = []): void
    {
        Log::info("[PromoCode Validation Failed] {$reason}", $context);
    }
}
