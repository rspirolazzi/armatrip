<?php

use Illuminate\Database\Seeder;

class StoresTableSeeder extends Seeder
{
    public static $STORES = [
        [
            'name' => 'Primarios',
            'code' => 'primarios',
            'active' => true,
        ],
        [
            'name' => 'Secundarios',
            'code' => 'secundarios',
            'active' => true,
        ],
        [
            'name' => 'Educativos',
            'code' => 'educativos',
            'active' => true,
        ],
        [
            'name' => '15 años',
            'code' => '15-anios',
            'active' => true,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$STORES as $row) {
            \App\Models\Store::create($row);
        }
    }
}
