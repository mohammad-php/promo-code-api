<?php

declare(strict_types=1);

use App\Enums\PromoCode\PromoCodeType;
use App\Models\PromoCode;
use App\Models\User;

it('fails to validate promo code for ineligible user', function () {
    $user = actingAsUser();

    $eligibleUser = User::factory()->create();

    $promo = PromoCode::factory()
        ->hasAttached($eligibleUser)
        ->create([
            'type' => PromoCodeType::Value,
            'value' => 10,
        ]);

    $payload = [
        'code' => $promo->code,
        'price' => 50.0,
    ];

    $this->postJson(route('promo-codes.validate'), $payload)
        ->assertNotFound()
        ->assertJsonFragment([
            'message' => __('messages.invalid_promo_code'),
        ]);

    $this->assertDatabaseMissing('promo_code_usages', [
        'promo_code_id' => $promo->id,
        'user_id' => $user->id,
    ]);
});

it('fails to validate an expired promo code', function () {
    $user = actingAsUser();

    $promo = PromoCode::factory()
        ->expired()
        ->create([
            'type' => PromoCodeType::Value,
            'value' => 15.0,
        ]);

    $this->postJson(route('promo-codes.validate'), [
        'code' => $promo->code,
        'price' => 100,
    ])
        ->assertNotFound()
        ->assertJsonFragment([
            'message' => __('messages.invalid_promo_code'),
        ]);

    $this->assertDatabaseMissing('promo_code_usages', [
        'promo_code_id' => $promo->id,
        'user_id' => $user->id,
    ]);
});

it('fails to validate a promo code after max uses exceeded', function () {
    $user = actingAsUser();

    $promo = PromoCode::factory()->create([
        'type' => PromoCodeType::Value,
        'value' => 10.0,
        'max_uses' => 1,
    ]);

    $this->postJson(route('promo-codes.validate'), [
        'code' => $promo->code,
        'price' => 50,
    ])->assertOk();

    $this->postJson(route('promo-codes.validate'), [
        'code' => $promo->code,
        'price' => 50,
    ])
        ->assertNotFound()
        ->assertJsonFragment([
            'message' => __('messages.invalid_promo_code'),
        ]);
});

it('fails when max uses per user is exceeded', function () {
    $user = actingAsUser();

    $promo = PromoCode::factory()->create([
        'type' => PromoCodeType::Value,
        'value' => 10,
        'max_uses_per_user' => 1,
    ]);

    $this->postJson(route('promo-codes.validate'), [
        'code' => $promo->code,
        'price' => 30,
    ])->assertOk();

    $this->postJson(route('promo-codes.validate'), [
        'code' => $promo->code,
        'price' => 30,
    ])
        ->assertNotFound()
        ->assertJsonFragment([
            'message' => __('messages.invalid_promo_code'),
        ]);
});

