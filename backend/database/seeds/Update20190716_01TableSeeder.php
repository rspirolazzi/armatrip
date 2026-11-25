<?php

use App\Admin;
use App\Models\Admin\Currency;
use App\Models\Admin\Province;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Update20190716_01TableSeeder extends Seeder
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
            Log::info('Se esta por ejecutar: Update20190716_01TableSeeder');
            $currencies = [
                ['name' => 'Pesos', 'code' => 'pesos', 'symbol' => '$'],
                ['name' => 'Dolares', 'code' => 'dollars', 'symbol' => 'USD'],
            ];
            foreach ($currencies as $value) {
                $currency = Currency::where('code', $value['code'])->first();
                if (!$currency) {
                    Log::info('Creando moneda', $value);
                    Currency::create($value);
                }
            }
            //Asignar pesos a las empresas que no son extranjeras
            $stores = Store::where('is_foreign', false)->get();
            $pesos = Currency::where('code', 'pesos')->first();
            foreach ($stores as $store) {
                $store->currency_id = $pesos->id;
                $store->save();
            }
            //Asignar dolares a las empresas que extranjeras
            $stores = Store::where('is_foreign', true)->get();
            $dollar = Currency::where('code', 'dollars')->first();
            foreach ($stores as $store) {
                $store->currency_id = $dollar->id;
                $store->save();
            }
            Log::info('Finalizacion de ejecucion: Update20190716_01TableSeeder');
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
