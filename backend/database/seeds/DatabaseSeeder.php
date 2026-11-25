<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriesTableSeeder::class);
        $this->call(StoresTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UserACLTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(PackageDateRangesSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(PackagesGroupSeeder::class);
        $this->call(ProductForMediaTableSeeder::class);
        $this->call(Update20190708_01TableSeeder::class);
        $this->call(Update20190716_01TableSeeder::class);
    }
}
