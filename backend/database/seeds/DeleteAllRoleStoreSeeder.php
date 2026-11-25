<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DeleteAllRoleStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::all()->each(function($value){
            $value->delete();
            Log::info('Se eliminaron todos los registros en Permisos', $value->toArray());
        });
        Role::all()->each(function($value){
            $value->delete();
            Log::info('Se eliminaron todos los registros en Roles', $value->toArray());
        });
    }
}