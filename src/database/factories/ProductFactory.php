<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
{
    return [
        'name' => $this->faker->word(),
        'description' => $this->faker->sentence(),
        'price' => $this->faker->randomFloat(2, 10, 500),
        'inventory' => $this->faker->numberBetween(0, 50),
        'avg_sales' => $this->faker->numberBetween(10, 50),
        'lead_time' => $this->faker->numberBetween(1, 7),
    ];
}

}
