<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\SettingManager;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends AppBaseController
{
    public function index(Request $request)
    {
        $settings = collect(SettingManager::all())->map(function ($value) {
            return array_merge($value->toArray(), ['old_value' => $value->value, 'changed' => false,]);
        })->values()->toArray();
        return view('admin.settings.index')
            ->with('settings', $settings);
    }

    public function update(Request $request)
    {
        try {
            Log::debug('Se intentara guardar', array_keys($request->get('values', [])));
            collect($request->get('values', []))->each(function ($value, $key) {
                SettingManager::set($key, $value);
                Log::debug("Se guardo $key");
            });
        } catch (\Exception $e) {
            Log::error('Ocurrio un error inesperado al intentar guardar las configuraciones', [$e->__toString()]);
        }
        return redirect(route('admin.settings.index'));
    }
}
