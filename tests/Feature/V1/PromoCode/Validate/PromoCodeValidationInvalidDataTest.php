<?php

declare(strict_types=1);

use App\Models\PromoCode;


it('fails to validate non-existing promo code', function () {
    actingAsUser();

    $this->postJson(route('promo-codes.validate'), [
        'code' => 'INVALID-CODE-123',
        'price' => 100,
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['code']);
});

it('fails when price is less than minimum', function () {
    $promo = PromoCode::factory()->create();

    actingAsUser();

    $this->postJson(route('promo-codes.validate'), [
        'code' => $promo->code,
        'price' => 0,
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['price']);
});
