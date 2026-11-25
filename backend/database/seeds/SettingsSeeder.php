<?php

use App\Models\Admin\SettingManager;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = collect([
            ['type' => SettingManager::$TYPE_STRING, 'key' => 'increment_percent', 'label' => 'Incremento', 'value' => 0],
            ['type' => SettingManager::$TYPE_SELECT, 'key' => 'tax_product_code', 'value' => 'bus',
                'label' => '',
                'options' => json_encode([
                    'from_model' => [
                        'model' => \App\Models\Admin\Category::class,
                        'option_key' => 'code',
                        'option_display' => 'name',
                    ]
                ])],
            ['type' => SettingManager::$TYPE_STRING, 'key' => 'default_country', 'value' => 'argentina', 'label' => 'País default',],
            ['type' => SettingManager::$TYPE_STRING, 'key' => 'mp_public_key_test', 'label' => 'Mercado Pago: Public key (Test)', 'value' => '', 'description'=>'Public key para utilizar para Test'],
            ['type' => SettingManager::$TYPE_STRING, 'key' => 'mp_access_token_test', 'label' => 'Mercado Pago: Access token (Test)', 'value' => '', 'description'=>'Access token para utilizar para Test'],
            ['type' => SettingManager::$TYPE_BOOLEAN, 'key' => 'mp_enabled_test', 'label' => 'Mercado Pago: Habilitar modo test', 'value' => 1, 'description'=>'Habilitar modo Test'],
            ['type' => SettingManager::$TYPE_STRING, 'key' => 'mp_public_key', 'label' => 'Mercado Pago: Public key', 'value' => '', 'description'=>'Public key para utilizar para producción'],
            ['type' => SettingManager::$TYPE_STRING, 'key' => 'mp_access_token', 'label' => 'Mercado Pago: Access token', 'value' => '', 'description'=>'Access token para utilizar para producción'],
            ['type' => SettingManager::$TYPE_BOOLEAN, 'key' => 'mp_enabled', 'label' => 'Habila el pago a través de Mercado Pago', 'value' => false, 'description'=>'Con esta acción se puede habilitar u ocultar el formulario de pagos en Armatrip (Cotizador)'],
            ['type' => SettingManager::$TYPE_DECIMAL, 'key' => 'mp_advance_amount_percentage', 'label' => 'Porcentaje de anticipo', 'value' => 0, 'description'=>'Indica el porcentaje de anticipo que se le solicitara al usuario que cotiza en Armatrip para pagar por Mercado Pago'],
        ]);
        $values->each(function ($value) {
            $setting = SettingManager::where('key', $value['key'])->first();
            if (!$setting) {
                SettingManager::create($value);
            }
        });
    }
}