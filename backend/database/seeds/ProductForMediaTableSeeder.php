<?php

use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductForMediaTableSeeder extends Seeder
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
            $order = 1;
            $code = Str::slug("Deco");
            $Deco = Category::where('code', $code)->first();
            if (!$Deco) {
                $Deco = Category::create(array(
                    "allowed_to_sell" => 1,
                    "name" => "Deco",
                    "code" => $code,
                    "parent_id" => NULL,
                    "order" => $order++,
                ));
            }
            $code = Str::slug("Logo");
            $Logo = Category::where('code', $code)->first();
            if (!$Logo) {
                $Logo = Category::create(array(
                    "allowed_to_sell" => 1,
                    "name" => "Logo",
                    "code" => $code,
                    "parent_id" => NULL,
                    "order" => $order++,
                ));
            }
            $code = Str::slug("Manual de Marca");
            $Manual_de_Marca = Category::where('code', $code)->first();
            if (!$Manual_de_Marca) {
                $Manual_de_Marca = Category::create(array(
                    "allowed_to_sell" => 1,
                    "name" => "Manual de Marca",
                    "code" => $code,
                    "parent_id" => NULL,
                    "order" => $order++,
                ));
            }
            $code = Str::slug("Merchandising");
            $Merchandising = Category::where('code', $code)->first();
            if (!$Merchandising) {
                $Merchandising = Category::create(array(
                    "allowed_to_sell" => 1,
                    "name" => "Merchandising",
                    "code" => $code,
                    "parent_id" => NULL,
                    "order" => $order++,
                ));
            }
            $code = Str::slug("RRSS");
            $RRSS = Category::where('code', $code)->first();
            if (!$RRSS) {
                $RRSS = Category::create(array(
                    "allowed_to_sell" => 1,
                    "name" => "RRSS",
                    "code" => $code,
                    "parent_id" => NULL,
                    "order" => $order++,
                ));
            }
            $code = Str::slug("UI Cotizador");
            $UI_Cotizador = Category::where('code', $code)->first();
            if (!$UI_Cotizador) {
                $UI_Cotizador = Category::create(array(
                    "allowed_to_sell" => 1,
                    "name" => "UI Cotizador",
                    "code" => $code,
                    "parent_id" => NULL,
                    "order" => $order++,
                ));
            }
            $code = Str::slug("Videos");
            $Videos = Category::where('code', $code)->first();
            if (!$Videos) {
                $Videos = Category::create(array(
                    "allowed_to_sell" => 1,
                    "name" => "Videos",
                    "code" => $code,
                    "parent_id" => NULL,
                    "order" => $order++,
                ));
            }

            $rows = [
                [
                    "category_id" => $Deco->id,
                    "name" => "Producto ejemplo de Deco",
                    "image_primary" => "",
                    "image_secondary" => "https://drive.google.com/open?id=1H85g7iLisc9_TMZqCdL0ZOcjioJTKt3i",
                ],
                [
                    "category_id" => $Logo->id,
                    "name" => "Producto ejemplo de Logo",
                    "image_primary" => "",
                    "image_secondary" => "https://drive.google.com/open?id=1H85g7iLisc9_TMZqCdL0ZOcjioJTKt3i",
                ],
                [
                    "category_id" => $Manual_de_Marca->id,
                    "name" => "Producto ejemplo de Manual_de_Marca",
                    "image_primary" => "",
                    "image_secondary" => "https://drive.google.com/open?id=1H85g7iLisc9_TMZqCdL0ZOcjioJTKt3i",
                ],
                [
                    "category_id" => $Merchandising->id,
                    "name" => "Producto ejemplo de Merchandising",
                    "image_primary" => "",
                    "image_secondary" => "https://drive.google.com/open?id=1H85g7iLisc9_TMZqCdL0ZOcjioJTKt3i",
                ],
                [
                    "category_id" => $RRSS->id,
                    "name" => "Producto ejemplo de RRSS",
                    "image_primary" => "",
                    "image_secondary" => "https://drive.google.com/open?id=1H85g7iLisc9_TMZqCdL0ZOcjioJTKt3i",
                ],
                [
                    "category_id" => $UI_Cotizador->id,
                    "name" => "Producto ejemplo de UI_Cotizador",
                    "image_primary" => "",
                    "image_secondary" => "https://drive.google.com/open?id=1H85g7iLisc9_TMZqCdL0ZOcjioJTKt3i",
                ],
                [
                    "category_id" => $Videos->id,
                    "name" => "Producto ejemplo de Videos",
                    "image_primary" => "",
                    "image_secondary" => "https://drive.google.com/open?id=1H85g7iLisc9_TMZqCdL0ZOcjioJTKt3i",
                ],
            ];

            foreach ($rows as $index => $row) {
                $code = Str::slug($row['name']);
                $product = Product::where('code', $code)->first();
                if (!$product) {
                    $row['description'] = $row['name'];
                    $row['code'] = $code;
                    $row['order'] = $index;
                    $row['price'] = 120;
                    $row['min'] = 1;
                    $row['max'] = 999;
                    $row['active'] = 1;
                    $row['allowed_to_sell'] = 1;
                    Product::create($row);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Ocurrio un error: ' . $e->getMessage());
            dd($e);
        }
    }
}
