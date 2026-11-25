<?php

use App\Models\Admin\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $transport = \App\Models\Admin\Category::where('code', 'transport')->first();
            $hotel = \App\Models\Admin\Category::where('code', 'hotel')->first();
            $excursions = \App\Models\Admin\Category::where('code', 'excursions')->first();
            $order = 0;
            $rows = [
                [
                    'name' => 'Bus',
                    'code' => Str::slug('Bus'),
                    'category_id' => $transport->id,
                    'price' => 5678,
                    'order' => $order,
                ], [
                    'name' => 'Aéreo',
                    'code' => Str::slug('Aéreo'),
                    'category_id' => $transport->id,
                    'price' => 15678,
                    'order' => $order,
                ], [
                    'name' => 'Hotel 5 estrellas',
                    'code' => Str::slug('Hotel 5 estrellas'),
                    'category_id' => $hotel->id,
                    'price' => 35678,
                    'order' => $order,
                ], [
                    'name' => 'Hotel 4 estrellas',
                    'code' => Str::slug('Hotel 4 estrellas'),
                    'category_id' => $hotel->id,
                    'price' => 25678,
                    'order' => $order,
                ], [
                    'name' => 'Hotel 3 estrellas',
                    'code' => Str::slug('Hotel 3 estrellas'),
                    'category_id' => $hotel->id,
                    'price' => 15678,
                    'order' => $order,
                ], [
                    'name' => 'Parque acuático',
                    'code' => Str::slug('Parque acuático'),
                    'category_id' => $excursions->id,
                    'price' => 14678,
                    'order' => $order,
                ], [
                    'name' => 'Discoteca',
                    'code' => Str::slug('Discoteca'),
                    'category_id' => $excursions->id,
                    'price' => 8678,
                    'order' => $order,
                ],
            ];
            foreach ($rows as $row) {
                Product::create($row);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }

    }
}
