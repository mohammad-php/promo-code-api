<?php

declare(strict_types=1);

use App\Enums\Auth\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

it('logs in successfully with valid credentials (admin)', function (): void {
    $user = User::factory()->create();
    $user->assignRole(UserRole::Admin->value);

    $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['access_token', 'token_type', 'user'])
                ->where('token_type', 'Bearer')
                ->where('user.id', $user->id)
                ->where('user.email', $user->email)
                ->where('user.name', $user->name)
        );
});

it('logs in successfully with valid credentials (user)', function (): void {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $user->assignRole(UserRole::User->value);

    $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['access_token', 'token_type', 'user'])
                ->where('token_type', 'Bearer')
                ->where('user.id', $user->id)
                ->where('user.email', $user->email)
                ->where('user.name', $user->name)
        );
});

it('fails when email and password are missing', function (): void {
    $this->postJson(route('login'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

it('fails when email is invalid format', function (): void {
    $this->postJson(route('login'), [
        'email' => 'not-an-email',
        'password' => 'password123',
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('fails when password is too short', function (): void {
    $this->postJson(route('login'), [
        'email' => 'user@example.com',
        'password' => 'short',
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('fails when password is incorrect', function (): void {
    $user = User::factory()->create([
        'password' => Hash::make('correct-password'),
    ]);
    $user->assignRole(UserRole::Admin->value);

    $this->postJson(route('login'), [
        'email' => 'admin@promo.com',
        'password' => 'wrong-password',
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('fails when email is not found', function (): void {
    $this->postJson(route('login'), [
        'email' => 'missing@example.com',
        'password' => 'password123',
    ])
        ->assertNotFound();
});
