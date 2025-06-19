<?php

declare(strict_types=1);

use App\Enums\PromoCode\PromoCodeType;
use App\Models\User;

it('stores promo code in DB with related users', function () {
    $admin = actingAsAdmin();
    $users = User::factory()->count(2)->create();

    $payload = [
        'type' => PromoCodeType::Value->value,
        'value' => 30,
        'user_ids' => $users->pluck('id')->toArray(),
    ];

    $response = $this->postJson(route('promo-codes.create'), $payload);

    $response->assertCreated();
    $this->assertDatabaseHas('promo_codes', ['code' => $response->json('code')]);

    foreach ($users as $user) {
        $this->assertDatabaseHas('promo_code_user', [
            'user_id' => $user->id,
        ]);
    }
});
