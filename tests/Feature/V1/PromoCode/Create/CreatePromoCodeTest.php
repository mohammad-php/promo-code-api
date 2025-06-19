<?php

declare(strict_types=1);

use App\Enums\PromoCode\PromoCodeType;
use App\Models\PromoCode;
use App\Models\User;
use App\Services\PromoCode\PromoCodeGeneratorService;

it('creates a promo code with all fields', function () {
    $admin = actingAsAdmin();

    $users = User::factory()->count(2)->create();
    $code = (new PromoCodeGeneratorService())->generate();

    $payload = [
        'code' => $code,
        'type' => PromoCodeType::Value->value,
        'value' => 30,
        'expires_at' => now()->addDays(10),
        'max_uses' => 100,
        'max_uses_per_user' => 2,
        'user_ids' => $users->pluck('id')->toArray(),
    ];

    $response = $this->postJson(route('promo-codes.create'), $payload);
    
    $response->assertCreated()
        ->assertJsonPath('code', $response->json('code'))
        ->assertJsonPath('type', $response->json('type'))
        ->assertJsonPath('value', $response->json('value'))
        ->assertJsonPath('expires_at', $response->json('expires_at'))
        ->assertJsonPath('max_uses', $response->json('max_uses'))
        ->assertJsonPath('max_uses_per_user', $response->json('max_uses_per_user'))
        ->assertJsonCount(2, 'users')
        ->assertJsonPath('users.0.id', $users[0]->id)
        ->assertJsonPath('users.1.id', $users[1]->id);
});

it('stores promo code in DB with related users', function () {
    $admin = actingAsAdmin();
    $users = User::factory()->count(2)->create();

    $payload = [
        'type' => PromoCodeType::Value->value,
        'value' => 30,
        'user_ids' => $users->pluck('id')->toArray(),
    ];

    $response = $this->postJson(route('promo-codes.create'), $payload)
        ->assertCreated();

    $this->assertDatabaseHas('promo_codes', ['code' => $response->json('code')]);

    foreach ($users as $user) {
        $this->assertDatabaseHas('promo_code_user', [
            'user_id' => $user->id,
        ]);
    }
});

it('creates a promo code with generated code when code is not provided', function () {
    actingAsAdmin();

    $payload = [
        'type' => PromoCodeType::Value->value,
        'value' => 25.5,
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertCreated()
        ->assertJson(fn ($json) => $json
            ->hasAll([
                'code',
                'type',
                'value',
                'expires_at',
                'max_uses',
                'max_uses_per_user',
                'users'
            ])
            ->where('type', PromoCodeType::Value->value)
            ->where('value', 25.5)
            ->where('expires_at', null)
            ->where('max_uses', null)
            ->where('max_uses_per_user', null)
            ->where('users', [])
        );
});

it('creates a promo code without optional fields', function () {
    actingAsAdmin();

    $basePromo = PromoCode::factory()->make();

    $payload = [
        'code' => $basePromo->code,
        'type' => $basePromo->type->value,
        'value' => $basePromo->value,
    ];

    $this->postJson(route('promo-codes.create'), $payload)
        ->assertCreated()
        ->assertJson(fn($json) => $json
            ->where('code', $basePromo->code)
            ->where('type', $basePromo->type->value)
            ->where('value', $basePromo->value)
            ->where('expires_at', null)
            ->where('max_uses', null)
            ->where('max_uses_per_user', null)
            ->where('users', [])
            ->etc()
        );
});
