<?php

use App\Models\Admin\PackageProduct;
use App\Models\Admin\Product;
use App\Models\Admin\Package;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        $storePrimario = Store::where('code', 'primarios')->first();
        $storeSecundario = Store::where('code', 'secundarios')->first();
        try {
            $order = 0;
            $rows = [
                //Viajes
                [
                    'name' => 'Cabo Frio',
                    'code' => Str::slug('Cabo Frio'),
                    'price' => 35678,
                    'order' => $order,
                    'store_id' => $storePrimario->id,
                ],
                [
                    'name' => 'Florianopolis',
                    'code' => Str::slug('Florianopolis'),
                    'price' => 45678,
                    'order' => $order,
                    'store_id' => $storeSecundario->id,
                ],
                [
                    'name' => 'Porto Seguro',
                    'code' => Str::slug('Porto Seguro'),
                    'price' => 55678,
                    'order' => $order,
                    'store_id' => $storePrimario->id,
                ],
                [
                    'name' => 'Bariloche',
                    'code' => Str::slug('Bariloche'),
                    'price' => 55678,
                    'order' => $order,
                    'store_id' => $storeSecundario->id,
                ],
                [
                    'name' => 'Villa Carlos Paz',
                    'code' => Str::slug('Villa Carlos Paz'),
                    'price' => 55678,
                    'order' => $order,
                    'store_id' => $storePrimario->id,
                ],
            ];
            foreach ($rows as $row) {
                Package::create($row);
            }

            $Cabo_Frio = Package::where('code', Str::slug('Cabo Frio'))->first();
            $Florianopolis = Package::where('code', Str::slug('Florianopolis'))->first();
            $Porto_Seguro = Package::where('code', Str::slug('Porto Seguro'))->first();
            $Bariloche = Package::where('code', Str::slug('Bariloche'))->first();
            $Villa_Carlos_Paz = Package::where('code', Str::slug('Villa Carlos Paz'))->first();

            $Bus = Product::where('code', Str::slug('Bus'))->first();
            $Aereo = Product::where('code', Str::slug('Aéreo'))->first();
            $Hotel5Star = Product::where('code', Str::slug('Hotel 5 estrellas'))->first();
            $Hotel4Star = Product::where('code', Str::slug('Hotel 4 estrellas'))->first();
            $Hotel3Star = Product::where('code', Str::slug('Hotel 3 estrellas'))->first();
            $Parque_acuatico = Product::where('code', Str::slug('Parque acuático'))->first();
            $Discoteca = Product::where('code', Str::slug('Discoteca'))->first();

            $packages = [
                $Cabo_Frio,
                $Florianopolis,
                $Porto_Seguro,
                $Bariloche,
                $Villa_Carlos_Paz,
            ];
            $rows = [];
            foreach ($packages as $p) {
                $rows[] = [
                    'package_id' => $p->id,
                    'product_id' => $Bus->id,
                    'price' => $Bus->price,
                    'default' => true,
                ];
                $rows[] = [
                    'package_id' => $p->id,
                    'product_id' => $Aereo->id,
                    'price' => $Aereo->price,
                    'default' => false,
                ];
                $rows[] = [
                    'package_id' => $p->id,
                    'product_id' => $Hotel5Star->id,
                    'price' => $Hotel5Star->price,
                    'default' => true,
                ];
                $rows[] = [
                    'package_id' => $p->id,
                    'product_id' => $Hotel4Star->id,
                    'price' => $Hotel4Star->price,
                    'default' => false,
                ];
                $rows[] = [
                    'package_id' => $p->id,
                    'product_id' => $Hotel3Star->id,
                    'price' => $Hotel3Star->price,
                    'default' => false,
                ];
                $rows[] = [
                    'package_id' => $p->id,
                    'product_id' => $Parque_acuatico->id,
                    'price' => $Parque_acuatico->price,
                    'default' => true,
                ];
                $rows[] = [
                    'package_id' => $p->id,
                    'product_id' => $Discoteca->id,
                    'price' => $Discoteca->price,
                    'default' => true,
                ];
            }
            foreach ($rows as $row) {
                PackageProduct::create($row);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
