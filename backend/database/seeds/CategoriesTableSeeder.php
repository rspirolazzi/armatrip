<?php

use App\Models\Admin\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order = 0;
        $rows = [
            [
                'name' => 'Paquetes',
                'code' => Str::slug('Package'),
                'order' => $order++,
                'multiple' => false,
                'required' => true,
            ],
            [
                'name' => 'Transportes',
                'code' => Str::slug('Transport'),
                'order' => $order++,
                'multiple' => false,
                'required' => true,
            ],
            [
                'name' => 'Hoteles',
                'code' => Str::slug('Hotel'),
                'order' => $order++,
                'multiple' => false,
                'required' => true,
            ],
            [
                'name' => 'Excursiones',
                'code' => Str::slug('Excursions'),
                'order' => $order++,
                'multiple' => true,
                'required' => false,
            ],
        ];
        foreach ($rows as $row) {
            Category::create($row);
        }
    }
}