<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserACLTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            Admin::$ROLE_ADMIN_IDG,
            Admin::$ROLE_ADMIN,
            Admin::$ROLE_EDITOR,
            Admin::$ROLE_QUOTING,
        ];
        $prefix = 'admin';
        $prefixIDG = 'idg';

        $permissions = [
            //Packages
            ['name' => $prefix . '.packages.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.packages.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.packages.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.clone', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.packages.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.dateRange', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.updateDateRange', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.photos', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.uploadPhotos', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.updatePhotos', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.videos', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.updateVideos', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.products', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.updateProducts', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packages.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.packages.simulation', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            //PackageGroups
            ['name' => $prefix . '.packageGroups.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packageGroups.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.packageGroups.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.packageGroups.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packageGroups.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packageGroups.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.packageGroups.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            //Media
            ['name' => $prefix . '.medias.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            //Products
            ['name' => $prefix . '.products.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.products.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.products.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.products.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.products.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.products.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.products.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.categories.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.categories.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.categories.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.categories.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.categories.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.categories.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR,]],
            ['name' => $prefix . '.categories.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.stores.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.stores.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.stores.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.stores.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.stores.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.stores.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.stores.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.stores.province', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.stores.provinceUpdate', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.administrators.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.administrators.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.administrators.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.administrators.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.administrators.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.administrators.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.administrators.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.users.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.users.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.users.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.users.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.users.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.users.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.users.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.orders.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_QUOTING,]],
            ['name' => $prefix . '.orders.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.orders.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.orders.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_QUOTING,]],
            ['name' => $prefix . '.orders.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_QUOTING,]],
            ['name' => $prefix . '.orders.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_QUOTING,]],
            ['name' => $prefix . '.orders.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_QUOTING,]],
            ['name' => $prefix . '.orders.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_QUOTING,]],
            ['name' => $prefix . '.orders.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.provinces.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.provinces.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.provinces.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.provinces.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.provinces.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.provinces.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.provinces.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.notifications.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.notifications.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.notifications.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.notifications.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.notifications.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.notifications.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.notifications.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.contacts.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.contacts.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.contacts.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.contacts.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.contacts.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.contacts.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.contacts.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.settings.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN]],
            ['name' => $prefix . '.settings.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN]],

            ['name' => $prefix . '.permissions.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN]],
            ['name' => $prefix . '.permissions.regenerate', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN]],

            //MediaInternalOrders
            ['name' => $prefixIDG . '.media_invoices.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.category', 'roles' => [Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.update_item', 'roles' => [Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.update_mass_item', 'roles' => [Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.create', 'roles' => [Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.confirm', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.destroy', 'roles' => [Admin::$ROLE_EDITOR]],

            ['name' => $prefixIDG . '.media_invoices.payment', 'roles' => [Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.sendPayment', 'roles' => [Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.selectPayment', 'roles' => [Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.creditCard', 'roles' => [Admin::$ROLE_EDITOR]],
            ['name' => $prefixIDG . '.media_invoices.cardPayment', 'roles' => [Admin::$ROLE_EDITOR]],

            ['name' => $prefixIDG . '.products.index', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.products.store', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.products.create', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.products.update', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.products.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.products.show', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.products.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.products.items', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.products.updateItems', 'roles' => [Admin::$ROLE_ADMIN_IDG]],

            ['name' => $prefixIDG . '.categories.index', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.categories.store', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.categories.create', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.categories.update', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.categories.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.categories.show', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefixIDG . '.categories.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG]],

            //Monedas
            ['name' => $prefix . '.currencies.index', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefix . '.currencies.store', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefix . '.currencies.create', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefix . '.currencies.update', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefix . '.currencies.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefix . '.currencies.show', 'roles' => [Admin::$ROLE_ADMIN_IDG]],
            ['name' => $prefix . '.currencies.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG]],

            //Brands
            ['name' => $prefix . '.brands.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, ]],
            ['name' => $prefix . '.brands.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.brands.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.brands.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.brands.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.brands.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.brands.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],

            //Schools
            ['name' => $prefix . '.schools.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, ]],
            ['name' => $prefix . '.schools.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.schools.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.schools.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.schools.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.schools.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.schools.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            //FamTours
            ['name' => $prefix . '.fam_tours.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, ]],
            ['name' => $prefix . '.fam_tours.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.fam_tours.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.fam_tours.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.fam_tours.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.fam_tours.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,]],
            ['name' => $prefix . '.fam_tours.sync', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, ]],
            //Coupons
            ['name' => 'admin.coupons.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,],],
            ['name' => 'admin.coupons.store', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,],],
            ['name' => 'admin.coupons.create', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,],],
            ['name' => 'admin.coupons.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,],],
            ['name' => 'admin.coupons.update', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,],],
            ['name' => 'admin.coupons.destroy', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,],],
            ['name' => 'admin.coupons.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,],],
            ['name' => 'admin.coupons.edit', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,],],
            ['name' => 'admin.coupons.usersIndex', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN,],],
            //MercadoPago
            ['name' => $prefix . '.mercadoPagos.index', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, ]],
            ['name' => $prefix . '.mercadoPagos.show', 'roles' => [Admin::$ROLE_ADMIN_IDG, Admin::$ROLE_ADMIN, ]],
        ];

        Log::debug('Creando roles');
        foreach ($roles as $role) {
            Role::findOrCreate($role, 'admin');
        }

        Log::debug('Creando permisos');
        foreach ($permissions as $permission) {
            try {
                Permission::findOrCreate($permission['name'], 'admin');
                foreach ($permission['roles'] as $key_role) {
                    $role = Role::findByName($key_role, 'admin');
                    $role->givePermissionTo($permission['name']);
                    Log::debug('Asignando permiso ' . $permission['name'] . ' al role ' . $key_role);
                }
            } catch (PermissionAlreadyExists $e) {
                Log::debug('Ya existe el permiso ' . $permission['name']);
            }
        }

        $user = Admin::where('email', 'admin@armatrip.test')->first();
        Log::debug('Asignando roles');
        if ($user) {
            $user->syncRoles(Admin::$ROLE_ADMIN);
        }
        $user = Admin::where('email', 'admin@idg.com')->first();
        Log::debug('Asignando roles');
        if ($user) {
            $user->syncRoles(Admin::$ROLE_ADMIN_IDG);
        }
        $user = Admin::where('email', 'editor@armatrip.test')->first();
        Log::debug('Asignando roles');
        if ($user) {
            $user->syncRoles(Admin::$ROLE_EDITOR);
        }
        $user = Admin::where('email', 'cotizador@armatrip.test')->first();
        Log::debug('Asignando roles');
        if ($user) {
            $user->syncRoles(Admin::$ROLE_QUOTING);
        }
    }
}
