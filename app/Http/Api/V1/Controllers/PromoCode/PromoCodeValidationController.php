<?php

declare(strict_types=1);

namespace App\Http\Api\V1\Controllers\PromoCode;

use App\DTOs\PromoCode\PromoCodeValidationResultData;
use App\DTOs\PromoCode\ValidatePromoCodeData;
use App\Http\Api\V1\Controllers\Controller;
use App\Services\PromoCode\PromoCodeService;
use App\Services\PromoCode\PromoCodeValidationService;
use Illuminate\Support\Facades\Auth;

/**
 * @group Promo Codes
 */
final class PromoCodeValidationController extends Controller
{
    public function __construct(
        private readonly PromoCodeService $service,
        private readonly PromoCodeValidationService $validationService,
    ) {
    }

    /**
     * Validate Promo Code
     *
     * @param ValidatePromoCodeData $data
     *
     * @return PromoCodeValidationResultData
     *
     * @bodyParam code string
     * Custom promo code, If not provided, one will be generated. <br>
     * Max 255 characters
     * Example: PROMO-1
     *
     * @bodyParam price decimal required
     * The discount value, (e.g., 10 for 10% or $10) <br>
     * Example: 10
     */
    public function __invoke(
        ValidatePromoCodeData $data,
    ): PromoCodeValidationResultData {
        $promoCode = $this->service->findByCodeOrFail($data->code);

        $user = Auth::user();

        return $this->validationService->validate(
            $promoCode,
            $user,
            $data->price,
        );
    }
}
