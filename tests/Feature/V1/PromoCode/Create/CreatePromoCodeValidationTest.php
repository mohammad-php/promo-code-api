<?php

declare(strict_types=1);

use App\Enums\PromoCode\PromoCodeType;
use App\Models\PromoCode;

it('fails when code is not unique', function () {
    actingAsAdmin();

    $existingPromo = PromoCode::factory()->create();

    $payload = [
        'code' => $existingPromo->code,
        'type' => PromoCodeType::Value->value,
        'value' => 10,
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['code']);
});

it('fails when the type field is missing', function () {
    actingAsAdmin();

    $basePromo = PromoCode::factory()->make();

    $payload = [
        'code' => $basePromo->code,
        'value' => $basePromo->value,
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['type']);
});

it('fails when the type field is invalid', function () {
    actingAsAdmin();

    $basePromo = PromoCode::factory()->make();

    $payload = [
        'code' => $basePromo->code,
        'type' => 'discount',
        'value' => $basePromo->value,
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['type']);
});

it('fails when value is less than 0.01', function () {
    actingAsAdmin();

    $payload = [
        'code' => fake()->unique()->regexify('INVALID-VALUE-[A-Z0-9]{3}'),
        'type' => PromoCodeType::Value->value,
        'value' => 0,
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['value']);
});

it('fails when value is not a number', function () {
    actingAsAdmin();

    $payload = [
        'code' => fake()->unique()->regexify('BADVAL-[A-Z0-9]{3}'),
        'type' => PromoCodeType::Percentage->value,
        'value' => 'not-a-number',
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['value']);
});

it('fails when expires_at is in the past (using expired factory state)', function () {
    actingAsAdmin();

    $expiredPromo = PromoCode::factory()->expired()->make([
        'code' => fake()->unique()->regexify('EXPIRED-[A-Z0-9]{3}'),
        'type' => PromoCodeType::Value,
        'value' => 20,
    ]);

    $payload = [
        'code' => $expiredPromo->code,
        'type' => $expiredPromo->type->value,
        'value' => $expiredPromo->value,
        'expires_at' => $expiredPromo->expires_at->toDateString(), // From factory
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['expires_at']);
});

it('fails when user_ids contain non-existent users', function () {
    actingAsAdmin();

    $invalidUserId = 999999;

    $payload = [
        'code' => fake()->unique()->regexify('BADUSERS-[A-Z0-9]{3}'),
        'type' => PromoCodeType::Percentage->value,
        'value' => 20,
        'user_ids' => [$invalidUserId],
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['user_ids.0']);
});

it('fails when user_ids is not an array', function () {
    actingAsAdmin();

    $payload = [
        'code' => fake()->unique()->regexify('BADARRAY-[A-Z0-9]{3}'),
        'type' => PromoCodeType::Value->value,
        'value' => 15.0,
        'user_ids' => 'not-an-array',
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['user_ids']);
});

it('fails when max_uses is less than 1', function () {
    actingAsAdmin();

    $payload = [
        'code' => fake()->unique()->regexify('INVALIDMAX-[A-Z0-9]{3}'),
        'type' => PromoCodeType::Value->value,
        'value' => 10,
        'max_uses' => 0,
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['max_uses']);
});

it('fails when max_uses_per_user is less than 1', function () {
    actingAsAdmin();

    $payload = [
        'code' => fake()->unique()->regexify('INVALID-MAX-USES-USER-[A-Z]{3}'),
        'type' => PromoCodeType::Percentage->value,
        'value' => 20.5,
        'max_uses_per_user' => 0,
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['max_uses_per_user']);
});
