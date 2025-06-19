<?php

declare(strict_types=1);

use App\Enums\PromoCode\PromoCodeType;
use App\Models\PromoCode;

it('validates a working promo code and returns discounted price', function () {
    $user = actingAsUser();

    $promo = PromoCode::factory()->create([
        'type' => PromoCodeType::Value,
        'value' => 10.0,
    ]);

    $payload = [
        'code' => $promo->code,
        'price' => 100.0,
    ];

    $this->postJson(route('promo-codes.validate'), $payload)
        ->assertOk()
        ->assertExactJson([
            'price' => 100,
            'discounted_amount' => 10,
            'final_price' => 90,
        ]);

    $this->assertDatabaseHas('promo_code_usages', [
        'promo_code_id' => $promo->id,
        'user_id' => $user->id,
    ]);
});

it('validates a percentage promo code and calculates correct discount', function () {
    $user = actingAsUser();

    $promo = PromoCode::factory()->create([
        'type' => PromoCodeType::Percentage,
        'value' => 20.0,
    ]);

    $payload = [
        'code' => $promo->code,
        'price' => 200.0,
    ];

    $this->postJson(route('promo-codes.validate'), $payload)
        ->assertOk()
        ->assertExactJson([
            'price' => 200,
            'discounted_amount' => 40,
            'final_price' => 160,
        ]);

    $this->assertDatabaseHas('promo_code_usages', [
        'promo_code_id' => $promo->id,
        'user_id' => $user->id,
    ]);
});

