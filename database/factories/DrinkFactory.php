<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\drink>
 */
class DrinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => str($name)->slug(),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 2, 8),
            'available' => $this->faker->boolean(80),
            'category_id' => Category::query()->inRandomOrder()->value('id') ?? Category::factory(),
            'image' => function () {
                // Ruta de origen (donde tienes las imágenes base)
                $sourceDir = public_path('images');

                // Imágenes disponibles
                $filename = 'bebida.jpg';
                $originalPath = $sourceDir . '/' . $filename;

                // Crear nombre único
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                $newFilename = Str::random(40) . '.' . $extension;

                // Ruta de destino en el disco 'public'
                $targetPath = 'drinks/' . $newFilename;

                // Copiar imagen al storage/app/public/*
                Storage::disk('public')->put($targetPath, file_get_contents($originalPath));

                // Guardar en la base de datos la ruta accesible públicamente
                return $targetPath;
            }
        ];
    }
}
