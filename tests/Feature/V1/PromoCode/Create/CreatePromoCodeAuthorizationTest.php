<?php

declare(strict_types=1);

use App\Enums\PromoCode\PromoCodeType;

it('forbids non-admin user from creating a promo code', function () {
    $user = actingAsUser();

    $payload = [
        'code' => fake()->unique()->regexify('NOTADMIN-[A-Z0-9]{3}'),
        'type' => PromoCodeType::Value->value,
        'value' => 25,
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertForbidden()
        ->assertJsonFragment(['message' => __('auth.unauthorized')]);
});
