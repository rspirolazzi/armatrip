<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\AppBaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Laracasts\Flash\Flash;
use Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends AppBaseController
{


    /**
     * Display a listing of the Address.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $permissions = Permission::all();

        return view('admin.permissions.index')
            ->with('permissions', $permissions);
    }

    public function regenerate()
    {
        try {
            Artisan::call('cache:forget', ['key' => 'spatie.permission.cache']);
            $user_acl_table_seeder = new \UserACLTableSeeder();
            $user_acl_table_seeder->run();
            Artisan::call('cache:forget', ['key' => 'spatie.permission.cache']);
            Flash::success('Los permisos se regeneraron con exito');
        } catch (\Exception $e) {
            dd($e);
        }
        return redirect(route('admin.permissions.index'));
    }

}
