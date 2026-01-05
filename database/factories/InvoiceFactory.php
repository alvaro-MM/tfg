<?php

namespace Database\Factories;

use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{

    public function definition(): array
    {
        return [
            'table_id' => Table::query()->inRandomOrder()->value('id') ?? Table::factory(),          // crea una mesa asociada
            'total'    => $this->faker->randomFloat(2, 5, 300), // 5.00 — 300.00
            'date'     => $this->faker->date(),
        ];
    }
}
