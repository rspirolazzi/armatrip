<?php

use App\Models\Admin\Package;
use App\Models\Admin\PackageDateRange;
use Illuminate\Database\Seeder;

class PackageDateRangesSeeder extends Seeder
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
            // Fechas de ejemplo, siempre en el futuro.
            $year = (int) date('Y') + 1;
            $codes = ['cabo-frio', 'florianopolis', 'porto-seguro', 'bariloche', 'villa-carlos-paz'];
            foreach (Package::whereIn('code', $codes)->get() as $package) {
                if (PackageDateRange::where('package_id', $package->id)->exists()) {
                    continue;
                }
                foreach ([1, 2] as $biweekly) {
                    PackageDateRange::create([
                        'package_id' => $package->id,
                        'name' => $biweekly . ' Quincena de Agosto',
                        'year' => $year,
                        'month' => 8,
                        'biweekly' => $biweekly,
                        'tax' => 30,
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
