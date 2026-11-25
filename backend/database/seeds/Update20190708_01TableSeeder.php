<?php

use App\Admin;
use App\Models\Admin\Province;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Update20190708_01TableSeeder extends Seeder
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
            Log::info('Se esta por ejecutar: Update20190708_01TableSeeder');
            Log::info('Creando Argentina');
            $code = 'argentina';
            $argentina = Province::where('code', $code)->first();
            if (!$argentina) {
                $argentina = Province::create([
                    'code' => $code,
                    'name' => 'Argentina',
                    'tax' => 0,
                    'active' => true,
                ]);
            }
            //Actualización de Provincias
            $row = [
                "buenos-aires",
                "catamarca",
                "chaco",
                "chubut",
                "cordoba",
                "corrientes",
                "entre-rios",
                "formosa",
                "jujuy",
                "la-pampa",
                "la-rioja",
                "mendoza",
                "misiones",
                "neuquen",
                "rio-negro",
                "salta",
                "san-juan",
                "santa-cruz",
                "santa-fe",
                "santiago-del-estero",
                "tierra-del-fuego",
                "tucuman",
                "san-luis",
            ];
            foreach ($row as $code) {
                $province = Province::where('code', $code)->first();
                if ($province) {
                    $province->parent_id = $argentina->id;
                    $province->update();
                }
            }
            //Actualización de paises
            $row = [
                "chile",
                "bolivia",
                "paraguay",
                "brasil",
                "uruguay",
            ];

            //Asignar argetina a las empresas que no son extranjeras
            $stores = Store::where('is_foreign', false)->get();
            foreach ($stores as $store) {
                $store->provinces()->sync([$argentina->id]);
            }
            //Asignar otros paises a las empresas que extranjeras
            $stores = Store::where('is_foreign', true)->get();
            $provinces_foreign = Province::whereIn('code', $row)->get();
            foreach ($stores as $store) {
                $store->provinces()->sync($provinces_foreign->map(function ($value) {
                    return $value->id;
                }));
            }
            Log::info('Finalizacion de ejecucion: Update20190708_01TableSeeder');
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
