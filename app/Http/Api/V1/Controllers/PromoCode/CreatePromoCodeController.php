<?php

declare(strict_types=1);

namespace App\Http\Api\V1\Controllers\PromoCode;

use App\DTOs\PromoCode\CreatePromoCodeData;
use App\Http\Api\V1\Controllers\Controller;
use App\Services\PromoCode\PromoCodeService;

/**
 * @group Promo Codes
 */
final class CreatePromoCodeController extends Controller
{
    public function __construct(
        private readonly PromoCodeService $service,
    ) {
    }

    /**
     * Create Promo Code
     *
     * @param  CreatePromoCodeData  $data
     *
     * @return CreatePromoCodeData
     *
     * @bodyParam code string
     * Custom promo code, If not provided, one will be generated. <br>
     * Max 255 characters
     * Example: PROMO-1
     *
     * @bodyParam type string required
     * Promo Code Type <br>
     * Must be a valid type. Enum: percentage, value <br>
     * Example: percentage
     *
     * @bodyParam value decimal required
     * The discount value, (e.g., 10 for 10% or $10) <br>
     * Example: 15
     *
     * @bodyParam expires_at date.
     * expiration date in YYYY-MM-DD format.
     * Example: 2025-12-31
     *
     * @bodyParam max_uses number
     * Max number of times the promo can be used in total <br>
     * Example: 100
     *
     * @bodyParam max_uses_per_user number
     * Max number of uses allowed per user <br>
     * Example: 2
     *
     * @bodyParam user_ids int[]
     * Array of user IDs as integers <br>
     * Must be valid user id (filtered by entity)
     * Example: [1,2]
     *
     */
    public function __invoke(
        CreatePromoCodeData $data,
    ): CreatePromoCodeData {
        $promoCode = $this->service->create($data);

        return CreatePromoCodeData::from($promoCode);
    }
}
