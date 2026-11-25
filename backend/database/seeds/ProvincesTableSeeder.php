<?php

use App\Models\Admin\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            [
                'name' => 'Buenos Aires',
                'code' => Str::slug('Buenos Aires'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Catamarca',
                'code' => Str::slug('Catamarca'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Chaco',
                'code' => Str::slug('Chaco'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Chubut',
                'code' => Str::slug('Chubut'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Córdoba',
                'code' => Str::slug('Córdoba'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Corrientes',
                'code' => Str::slug('Corrientes'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Entre Ríos',
                'code' => Str::slug('Entre Ríos'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Formosa',
                'code' => Str::slug('Formosa'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Jujuy',
                'code' => Str::slug('Jujuy'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'La Pampa',
                'code' => Str::slug('La Pampa'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'La Rioja',
                'code' => Str::slug('La Rioja'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Mendoza',
                'code' => Str::slug('Mendoza'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Misiones',
                'code' => Str::slug('Misiones'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Neuquén',
                'code' => Str::slug('Neuquén'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Río Negro',
                'code' => Str::slug('Río Negro'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Salta',
                'code' => Str::slug('Salta'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'San Juan',
                'code' => Str::slug('San Juan'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Santa Cruz',
                'code' => Str::slug('Santa Cruz'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Santa Fe',
                'code' => Str::slug('Santa Fe'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Santiago del Estero',
                'code' => Str::slug('Santiago del Estero'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Tierra del Fuego',
                'code' => Str::slug('Tierra del Fuego'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Chile',
                'code' => Str::slug('Chile'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Bolivia',
                'code' => Str::slug('Bolivia'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Paraguay',
                'code' => Str::slug('Paraguay'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Brasil',
                'code' => Str::slug('Brasil'),
                'tax' => 0,
                'active' => true,
            ],
            [
                'name' => 'Uruguay',
                'code' => Str::slug('Uruguay'),
                'tax' => 0,
                'active' => true,
            ],
        ];
        foreach ($rows as $row) {
            $value = Province::where('code', $row['code'])->first();
            if (!$value) {
                Province::create($row);
            } else {
                $value->update($row);
            }
        }
    }
}
