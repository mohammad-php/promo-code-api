<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PromoCode\PromoCodeType;
use App\Models\PromoCode;
use App\Models\User;
use App\Services\PromoCode\PromoCodeGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
final class PromoCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromoCode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => (new PromoCodeGeneratorService())->generate(),
            'type' => $this->faker->randomElement([
                PromoCodeType::Percentage,
                PromoCodeType::Value,
            ]),
            'value' => $this->faker->randomFloat(2, 1, 50),
            'expires_at' => now()->addDays($this->faker->numberBetween(1, 30)),
            'max_uses' => $this->faker->numberBetween(1, 100),
            'max_uses_per_user' => $this->faker->numberBetween(1, 5),
        ];
    }


    /**
     * @return $this
     */
    public function expired(): static
    {
        return $this->state(fn () => [
            'expires_at' => now()->subDays(1),
        ]);
    }
}
