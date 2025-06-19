<?php

declare(strict_types=1);

namespace App\Services\PromoCode;

use App\DTOs\PromoCode\CreatePromoCodeData;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class PromoCodeService
{
    /**
     * @param  PromoCodeGeneratorService  $generator
     */
    public function __construct(
        private readonly PromoCodeGeneratorService $generator,
    ) {
    }

    /**
     * @param  CreatePromoCodeData  $data
     *
     * @return PromoCode
     */
    public function create(CreatePromoCodeData $data): PromoCode
    {
        return DB::transaction(function () use ($data): PromoCode {
            $promoCode = PromoCode::create([
                ...$data->toArray(),
                'code' => $data->code ?? $this->generator->generate(),
            ]);

            $this->syncUsers($promoCode, $data);

            return $promoCode->load('users');
        });
    }

    /**
     * @param string $code
     *
     * @return PromoCode
     */
    public function findByCodeOrFail(string $code): PromoCode
    {
        // To Do: Invalidate cache when promo code is updated/deleted

        $key = "promo_code:$code";

        Cache::has($key) ? Log::info("Cache HIT for $key"):
            Log::info("Cache MISS for $key");

        return Cache::remember(
            $key,
            now()->addMinutes(10),
            fn () => PromoCode::whereCode($code)->firstOrFail()
        );
    }

    /**
     * @param  PromoCode  $promoCode
     * @param  CreatePromoCodeData  $data
     *
     * @return void
     */
    private function syncUsers(
        PromoCode $promoCode,
        CreatePromoCodeData $data
    ): void {
        $users = $data->users->toCollection();

        if ($users->isNotEmpty()) {
            $promoCode->users()->sync(
                $users->pluck('id')->all()
            );
        }
    }
}
