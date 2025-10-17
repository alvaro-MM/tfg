<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\allergen;

class AllergenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear todos los alérgenos conocidos (o al menos varios)
        $names = [
            'Cereales con gluten (Trigo, centeno, cebada, avena, espelta, kamut y sus productos)',
            'Crustáceos (cangrejos, langostas, gambas, etc.)',
            'Huevos',
            'Pescado',
            'Cacahuetes',
            'Soja',
            'Leche (incluidos productos lácteos y lactosa)',
            'Frutos de cáscara (almendras, avellanas, nueces, anacardos, pacanas, nueces de Brasil, pistachos, nueces de macadamia)',
            'Apio (en condimentos, sopas, cremas y productos cárnicos)',
            'Mostaza (presente en panes, salsas, marinados y productos cárnicos)',
            'Granos de sésamo (semillas, pasta tahine y aceites)',
            'Dióxido de azufre y sulfitos (como conservantes en frutas desecadas, vino y cerveza, >10 mg/kg o >10 mg/L)',
            'Altramuces (semillas y harinas, a veces presentes en pan y pasteles)',
            'Moluscos (mejillones, almejas, caracoles, pulpos, etc.)'
        ];
        foreach ($names as $name) {
            allergen::factory()->create(['name' => $name, 'slug' => str($name)->slug()]);
        }
    }
}
