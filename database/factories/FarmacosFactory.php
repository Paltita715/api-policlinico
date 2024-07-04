<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farmacos>
 */
class FarmacosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->word,
            'compuesto' => $this->faker->word,
            'unidades_por_paquete' => $this->faker->numberBetween(1, 12),
            'descripcion' => $this->faker->sentence(),
            'reg_sanitario'=> $this->faker->regexify('[A-Z0-9]{12}'),
            'stock' => $this->faker->numberBetween(0, 10000)
        ];
    }
}
