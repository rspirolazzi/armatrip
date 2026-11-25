<?php

use App\Models\Admin\Package;
use App\Models\Admin\PackageGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PackagesGroupSeeder extends Seeder
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
            Log::info('Se esta por ejecutar: PackagesGroupSeeder');
            $order = 0;
            $rows = array(
                array(
                    "name" => "Cabo Frio",
                    "order" => $order++,
                ),
                array(
                    "name" => "Florianopolis",
                    "order" => $order++,
                ),
                array(
                    "name" => "Porto Seguro",
                    "order" => $order++,
                ),
                array(
                    "name" => "Carlos Paz",
                    "order" => $order++,
                ),
                array(
                    "name" => "Jurere",
                    "order" => $order++,
                ),
                array(
                    "name" => "San Pedro",
                    "order" => $order++,
                ),
                array(
                    "name" => "Bariloche",
                    "order" => $order++,
                ),
                array(
                    "name" => "Camboriu",
                    "order" => $order++,
                ),
                array(
                    "name" => "Chile",
                    "order" => $order++,
                ),
            );

            foreach ($rows as $row) {
                $row['code'] = Str::slug($row['name']);
                $group = PackageGroup::where('code', $row['code'])->first();
                if (!$group) {
                    PackageGroup::create($row);
                }
            }

            // La API solo devuelve paquetes con un grupo activo.
            $groups = [
                'cabo-frio' => 'cabo-frio',
                'florianopolis' => 'florianopolis',
                'porto-seguro' => 'porto-seguro',
                'bariloche' => 'bariloche',
                'villa-carlos-paz' => 'carlos-paz',
            ];
            foreach ($groups as $package_code => $group_code) {
                $group = PackageGroup::where('code', $group_code)->firstOrFail();
                Package::where('code', $package_code)
                    ->whereNull('package_group_id')
                    ->update(['package_group_id' => $group->id]);
            }
            Log::info('Finalizacion de ejecucion: PackagesGroupSeeder');
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
