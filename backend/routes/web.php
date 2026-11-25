<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Events\OrdersStatus;

Route::get('/', function () {
    return redirect(route('admin.index'));
});
//Route::get('/', 'HomeController@index');;

Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => ['authAdmin:admin', 'roleOrPermission:' . \App\Admin::getRoleOrPermission()]], function () {

    Route::get('/', 'Admin\DashboardController@index')->name('admin.index');
    Route::get('orders/{orders}/email', ['as' => 'admin.orders.email', 'uses' => 'Admin\OrderController@email']);
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');



    //Export to Excel
    Route::get('orders/export', ['as' => 'admin.orders.export', 'uses' => 'Admin\OrderController@export']);

    Route::group(['middleware' => ['permissionForRoute']], function () {

        Route::get('coupons', ['as' => 'admin.coupons.index', 'uses' => 'Admin\CouponController@index'])->middleware('filterRequest');
        Route::post('coupons', ['as' => 'admin.coupons.store', 'uses' => 'Admin\CouponController@store']);
        Route::get('coupons/create', ['as' => 'admin.coupons.create', 'uses' => 'Admin\CouponController@create']);
        Route::put('coupons/{coupons}', ['as' => 'admin.coupons.update', 'uses' => 'Admin\CouponController@update']);
        Route::patch('coupons/{coupons}', ['as' => 'admin.coupons.update', 'uses' => 'Admin\CouponController@update']);
        Route::delete('coupons/{coupons}', ['as' => 'admin.coupons.destroy', 'uses' => 'Admin\CouponController@destroy']);
        Route::get('coupons/{coupons}', ['as' => 'admin.coupons.show', 'uses' => 'Admin\CouponController@show']);
        Route::get('coupons/{coupons}/edit', ['as' => 'admin.coupons.edit', 'uses' => 'Admin\CouponController@edit']);

        Route::get('couponsusers', ['as' => 'admin.coupons.usersIndex', 'uses' => 'Admin\CouponUserController@index']);

        //Categories
        Route::get('categories', ['as' => 'admin.categories.index', 'uses' => 'Admin\CategoryController@index'])->middleware('filterRequest');
        Route::post('categories', ['as' => 'admin.categories.store', 'uses' => 'Admin\CategoryController@store']);
        Route::get('categories/create', ['as' => 'admin.categories.create', 'uses' => 'Admin\CategoryController@create']);
        Route::put('categories/{categories}', ['as' => 'admin.categories.update', 'uses' => 'Admin\CategoryController@update']);
        Route::patch('categories/{categories}', ['as' => 'admin.categories.update', 'uses' => 'Admin\CategoryController@update']);
        Route::delete('categories/{categories}', ['as' => 'admin.categories.destroy', 'uses' => 'Admin\CategoryController@destroy']);
        Route::get('categories/{categories}', ['as' => 'admin.categories.show', 'uses' => 'Admin\CategoryController@show']);
        Route::get('categories/{categories}/edit', ['as' => 'admin.categories.edit', 'uses' => 'Admin\CategoryController@edit']);

        //Orders
        Route::get('orders', ['as' => 'admin.orders.index', 'uses' => 'Admin\OrderController@index'])->middleware('filterRequest');
        Route::get('orders/{orders}', ['as' => 'admin.orders.show', 'uses' => 'Admin\OrderController@show']);
        Route::get('orders/{orders}/print', ['as' => 'admin.orders.print', 'uses' => 'Admin\OrderController@print']);
        Route::get('orders/{orders}/edit', ['as' => 'admin.orders.edit', 'uses' => 'Admin\OrderController@edit']);
        Route::put('orders/{orders}/update', ['as' => 'admin.orders.update', 'uses' => 'Admin\OrderController@update']);
        Route::patch('orders/{orders}/update', ['as' => 'admin.orders.update', 'uses' => 'Admin\OrderController@update']);

        //Contacts
        Route::get('contacts', ['as' => 'admin.contacts.index', 'uses' => 'Admin\ContactController@index'])->middleware('filterRequest');
        Route::delete('contacts/{contacts}', ['as' => 'admin.contacts.destroy', 'uses' => 'Admin\ContactController@destroy']);
        Route::get('contacts/{contacts}', ['as' => 'admin.contacts.show', 'uses' => 'Admin\ContactController@show']);

        //PackageGroups
        Route::get('package-groups', ['as' => 'admin.packageGroups.index', 'uses' => 'Admin\PackageGroupController@index'])->middleware('filterRequest');
        Route::post('package-groups', ['as' => 'admin.packageGroups.store', 'uses' => 'Admin\PackageGroupController@store']);
        Route::get('package-groups/create', ['as' => 'admin.packageGroups.create', 'uses' => 'Admin\PackageGroupController@create']);
        Route::put('package-groups/{packages}', ['as' => 'admin.packageGroups.update', 'uses' => 'Admin\PackageGroupController@update']);
        Route::patch('package-groups/{packages}', ['as' => 'admin.packageGroups.update', 'uses' => 'Admin\PackageGroupController@update']);
        Route::delete('package-groups/{packages}', ['as' => 'admin.packageGroups.destroy', 'uses' => 'Admin\PackageGroupController@destroy']);
        Route::get('package-groups/{packages}', ['as' => 'admin.packageGroups.show', 'uses' => 'Admin\PackageGroupController@show']);
        Route::get('package-groups/{packages}/edit', ['as' => 'admin.packageGroups.edit', 'uses' => 'Admin\PackageGroupController@edit']);
        //Media
        Route::get('medias', ['as' => 'admin.medias.index', 'uses' => 'Admin\MediaController@index'])->middleware('filterRequest');
        //Packages
        Route::get('packages', ['as' => 'admin.packages.index', 'uses' => 'Admin\PackageController@index'])->middleware('filterRequest');
        Route::post('packages', ['as' => 'admin.packages.store', 'uses' => 'Admin\PackageController@store']);
        Route::get('packages/create', ['as' => 'admin.packages.create', 'uses' => 'Admin\PackageController@create']);
        Route::put('packages/{packages}', ['as' => 'admin.packages.update', 'uses' => 'Admin\PackageController@update']);
        Route::patch('packages/{packages}', ['as' => 'admin.packages.update', 'uses' => 'Admin\PackageController@update']);
        Route::delete('packages/{packages}', ['as' => 'admin.packages.destroy', 'uses' => 'Admin\PackageController@destroy']);
        Route::get('packages/{packages}', ['as' => 'admin.packages.show', 'uses' => 'Admin\PackageController@show']);
        Route::get('packages/{packages}/edit', ['as' => 'admin.packages.edit', 'uses' => 'Admin\PackageController@edit']);
        Route::get('packages/{packages}/simulation', ['as' => 'admin.packages.simulation', 'uses' => 'Admin\PackageController@simulation']);
        Route::post('packages/{packages}/simulation', ['as' => 'admin.packages.simulation', 'uses' => 'Admin\PackageController@simulation']);
        Route::post('packages/clone', ['as' => 'admin.packages.clone', 'uses' => 'Admin\PackageController@clone']);

        Route::get('packages/{packages}/products', ['as' => 'admin.packages.products', 'uses' => 'Admin\PackageController@products']);
        Route::put('packages/{packages}/updateProducts', ['as' => 'admin.packages.updateProducts', 'uses' => 'Admin\PackageController@updateProducts']);
        Route::patch('packages/{packages}/updateProducts', ['as' => 'admin.packages.updateProducts', 'uses' => 'Admin\PackageController@updateProducts']);

        Route::get('packages/{packages}/dateRange', ['as' => 'admin.packages.dateRange', 'uses' => 'Admin\PackageController@dateRange']);
        Route::put('packages/{packages}/updateDateRange', ['as' => 'admin.packages.updateDateRange', 'uses' => 'Admin\PackageController@updateDateRange']);
        Route::patch('packages/{packages}/updateDateRange', ['as' => 'admin.packages.updateDateRange', 'uses' => 'Admin\PackageController@updateDateRange']);

        Route::get('packages/{packages}/photos', ['as' => 'admin.packages.photos', 'uses' => 'Admin\PackageController@photos']);
        Route::put('packages/{packages}/updatePhotos', ['as' => 'admin.packages.updatePhotos', 'uses' => 'Admin\PackageController@updatePhotos']);
        Route::patch('packages/{packages}/updatePhotos', ['as' => 'admin.packages.updatePhotos', 'uses' => 'Admin\PackageController@updatePhotos']);
        Route::post('packages/{packages}/uploadPhotos', ['as' => 'admin.packages.uploadPhotos', 'uses' => 'Admin\PackageController@uploadPhotos']);

        Route::get('packages/{packages}/videos', ['as' => 'admin.packages.videos', 'uses' => 'Admin\PackageController@videos']);
        Route::put('packages/{packages}/updateVideos', ['as' => 'admin.packages.updateVideos', 'uses' => 'Admin\PackageController@updateVideos']);
        Route::patch('packages/{packages}/updateVideos', ['as' => 'admin.packages.updateVideos', 'uses' => 'Admin\PackageController@updateVideos']);

        Route::get('products', ['as' => 'admin.products.index', 'uses' => 'Admin\ProductController@index'])->middleware('filterRequest');
        Route::post('products', ['as' => 'admin.products.store', 'uses' => 'Admin\ProductController@store']);
        Route::get('products/create', ['as' => 'admin.products.create', 'uses' => 'Admin\ProductController@create']);
        Route::put('products/{products}', ['as' => 'admin.products.update', 'uses' => 'Admin\ProductController@update']);
        Route::patch('products/{products}', ['as' => 'admin.products.update', 'uses' => 'Admin\ProductController@update']);
        Route::delete('products/{products}', ['as' => 'admin.products.destroy', 'uses' => 'Admin\ProductController@destroy']);
        Route::get('products/{products}', ['as' => 'admin.products.show', 'uses' => 'Admin\ProductController@show']);
        Route::get('products/{products}/edit', ['as' => 'admin.products.edit', 'uses' => 'Admin\ProductController@edit']);

        Route::get('products/{products}/items', ['as' => 'admin.products.items', 'uses' => 'Admin\ProductController@items']);
        Route::put('products/{products}/updateItems', ['as' => 'admin.products.updateItems', 'uses' => 'Admin\ProductController@updateItems']);
        Route::patch('products/{products}/updateItems', ['as' => 'admin.products.updateItems', 'uses' => 'Admin\ProductController@updateItems']);

        // Stores

        Route::get('stores', ['as' => 'admin.stores.index', 'uses' => 'Admin\StoreController@index'])->middleware('filterRequest');
        Route::post('stores', ['as' => 'admin.stores.store', 'uses' => 'Admin\StoreController@store']);
        Route::get('stores/create', ['as' => 'admin.stores.create', 'uses' => 'Admin\StoreController@create']);
        Route::put('stores/{stores}', ['as' => 'admin.stores.update', 'uses' => 'Admin\StoreController@update']);
        Route::patch('stores/{stores}', ['as' => 'admin.stores.update', 'uses' => 'Admin\StoreController@update']);
        Route::delete('stores/{stores}', ['as' => 'admin.stores.destroy', 'uses' => 'Admin\StoreController@destroy']);
        Route::get('stores/{stores}', ['as' => 'admin.stores.show', 'uses' => 'Admin\StoreController@show']);
        Route::get('stores/{stores}/edit', ['as' => 'admin.stores.edit', 'uses' => 'Admin\StoreController@edit']);
        Route::get('stores/{stores}/province', ['as' => 'admin.stores.province', 'uses' => 'Admin\StoreController@province']);
        Route::put('stores/{stores}/provinceUpdate', ['as' => 'admin.stores.provinceUpdate', 'uses' => 'Admin\StoreController@provinceUpdate']);
        Route::patch('stores/{stores}/provinceUpdate', ['as' => 'admin.stores.provinceUpdate', 'uses' => 'Admin\StoreController@provinceUpdate']);

        // Administrators

        Route::get('administrators', ['as' => 'admin.administrators.index', 'uses' => 'Admin\AdministratorController@index'])->middleware('filterRequest');
        Route::post('administrators', ['as' => 'admin.administrators.store', 'uses' => 'Admin\AdministratorController@store']);
        Route::get('administrators/create', ['as' => 'admin.administrators.create', 'uses' => 'Admin\AdministratorController@create']);
        Route::put('administrators/{administrators}', ['as' => 'admin.administrators.update', 'uses' => 'Admin\AdministratorController@update']);
        Route::patch('administrators/{administrators}', ['as' => 'admin.administrators.update', 'uses' => 'Admin\AdministratorController@update']);
        Route::delete('administrators/{administrators}', ['as' => 'admin.administrators.destroy', 'uses' => 'Admin\AdministratorController@destroy']);
        Route::get('administrators/{administrators}', ['as' => 'admin.administrators.show', 'uses' => 'Admin\AdministratorController@show']);
        Route::get('administrators/{administrators}/edit', ['as' => 'admin.administrators.edit', 'uses' => 'Admin\AdministratorController@edit']);

        // Users

        Route::get('users', ['as' => 'admin.users.index', 'uses' => 'Admin\UserController@index'])->middleware('filterRequest');
        Route::post('users', ['as' => 'admin.users.store', 'uses' => 'Admin\UserController@store']);
        Route::get('users/create', ['as' => 'admin.users.create', 'uses' => 'Admin\UserController@create']);
        Route::put('users/{users}', ['as' => 'admin.users.update', 'uses' => 'Admin\UserController@update']);
        Route::patch('users/{users}', ['as' => 'admin.users.update', 'uses' => 'Admin\UserController@update']);
        Route::delete('users/{users}', ['as' => 'admin.users.destroy', 'uses' => 'Admin\UserController@destroy']);
        Route::get('users/{users}', ['as' => 'admin.users.show', 'uses' => 'Admin\UserController@show']);
        Route::get('users/{users}/edit', ['as' => 'admin.users.edit', 'uses' => 'Admin\UserController@edit']);


        //Provincias

        Route::get('provinces', ['as' => 'admin.provinces.index', 'uses' => 'Admin\ProvinceController@index'])->middleware('filterRequest');
        Route::post('provinces', ['as' => 'admin.provinces.store', 'uses' => 'Admin\ProvinceController@store']);
        Route::get('provinces/create', ['as' => 'admin.provinces.create', 'uses' => 'Admin\ProvinceController@create']);
        Route::put('provinces/{provinces}', ['as' => 'admin.provinces.update', 'uses' => 'Admin\ProvinceController@update']);
        Route::patch('provinces/{provinces}', ['as' => 'admin.provinces.update', 'uses' => 'Admin\ProvinceController@update']);
        Route::delete('provinces/{provinces}', ['as' => 'admin.provinces.destroy', 'uses' => 'Admin\ProvinceController@destroy']);
        Route::get('provinces/{provinces}', ['as' => 'admin.provinces.show', 'uses' => 'Admin\ProvinceController@show']);
        Route::get('provinces/{provinces}/edit', ['as' => 'admin.provinces.edit', 'uses' => 'Admin\ProvinceController@edit']);
        //Monedas

        Route::get('currencies', ['as' => 'admin.currencies.index', 'uses' => 'Admin\CurrencyController@index'])->middleware('filterRequest');
        Route::post('currencies', ['as' => 'admin.currencies.store', 'uses' => 'Admin\CurrencyController@store']);
        Route::get('currencies/create', ['as' => 'admin.currencies.create', 'uses' => 'Admin\CurrencyController@create']);
        Route::put('currencies/{currencies}', ['as' => 'admin.currencies.update', 'uses' => 'Admin\CurrencyController@update']);
        Route::patch('currencies/{currencies}', ['as' => 'admin.currencies.update', 'uses' => 'Admin\CurrencyController@update']);
        Route::delete('currencies/{currencies}', ['as' => 'admin.currencies.destroy', 'uses' => 'Admin\CurrencyController@destroy']);
        Route::get('currencies/{currencies}', ['as' => 'admin.currencies.show', 'uses' => 'Admin\CurrencyController@show']);
        Route::get('currencies/{currencies}/edit', ['as' => 'admin.currencies.edit', 'uses' => 'Admin\CurrencyController@edit']);

        //Contacto

        Route::get('contacts', ['as' => 'admin.contacts.index', 'uses' => 'Admin\ContactController@index'])->middleware('filterRequest');
        Route::post('contacts', ['as' => 'admin.contacts.store', 'uses' => 'Admin\ContactController@store']);
        Route::get('contacts/create', ['as' => 'admin.contacts.create', 'uses' => 'Admin\ContactController@create']);
        Route::put('contacts/{contacts}', ['as' => 'admin.contacts.update', 'uses' => 'Admin\ContactController@update']);
        Route::patch('contacts/{contacts}', ['as' => 'admin.contacts.update', 'uses' => 'Admin\ContactController@update']);
        Route::delete('contacts/{contacts}', ['as' => 'admin.contacts.destroy', 'uses' => 'Admin\ContactController@destroy']);
        Route::get('contacts/{contacts}', ['as' => 'admin.contacts.show', 'uses' => 'Admin\ContactController@show']);
        Route::get('contacts/{contacts}/edit', ['as' => 'admin.contacts.edit', 'uses' => 'Admin\ContactController@edit']);


        //Notificaciones

        Route::get('notifications', ['as' => 'admin.notifications.index', 'uses' => 'Admin\NotificationController@index'])->middleware('filterRequest');
        Route::post('notifications', ['as' => 'admin.notifications.store', 'uses' => 'Admin\NotificationController@store']);
        Route::get('notifications/create', ['as' => 'admin.notifications.create', 'uses' => 'Admin\NotificationController@create']);
        Route::put('notifications/{notifications}', ['as' => 'admin.notifications.update', 'uses' => 'Admin\NotificationController@update']);
        Route::patch('notifications/{notifications}', ['as' => 'admin.notifications.update', 'uses' => 'Admin\NotificationController@update']);
        Route::delete('notifications/{notifications}', ['as' => 'admin.notifications.destroy', 'uses' => 'Admin\NotificationController@destroy']);
        Route::get('notifications/{notifications}', ['as' => 'admin.notifications.show', 'uses' => 'Admin\NotificationController@show']);
        Route::get('notifications/{notifications}/edit', ['as' => 'admin.notifications.edit', 'uses' => 'Admin\NotificationController@edit']);

        Route::get('permissions', ['as' => 'admin.permissions.index', 'uses' => 'Admin\Configuration\PermissionController@index'])->middleware('filterRequest');
        Route::post('permissions/regenerate', ['as' => 'admin.permissions.regenerate', 'uses' => 'Admin\Configuration\PermissionController@regenerate']);

        //Brands
        Route::get('brands', ['as' => 'admin.brands.index', 'uses' => 'Admin\BrandController@index'])->middleware('filterRequest');
        Route::post('brands', ['as' => 'admin.brands.store', 'uses' => 'Admin\BrandController@store']);
        Route::get('brands/create', ['as' => 'admin.brands.create', 'uses' => 'Admin\BrandController@create']);
        Route::put('brands/{brands}', ['as' => 'admin.brands.update', 'uses' => 'Admin\BrandController@update']);
        Route::patch('brands/{brands}', ['as' => 'admin.brands.update', 'uses' => 'Admin\BrandController@update']);
        Route::delete('brands/{brands}', ['as' => 'admin.brands.destroy', 'uses' => 'Admin\BrandController@destroy']);
        Route::get('brands/{brands}', ['as' => 'admin.brands.show', 'uses' => 'Admin\BrandController@show']);
        Route::get('brands/{brands}/edit', ['as' => 'admin.brands.edit', 'uses' => 'Admin\BrandController@edit']);

        //Schools
        Route::get('schools', ['as' => 'admin.schools.index', 'uses' => 'Admin\SchoolController@index'])->middleware('filterRequest');
        Route::post('schools', ['as' => 'admin.schools.store', 'uses' => 'Admin\SchoolController@store']);
        Route::get('schools/create', ['as' => 'admin.schools.create', 'uses' => 'Admin\SchoolController@create']);
        Route::put('schools/{schools}', ['as' => 'admin.schools.update', 'uses' => 'Admin\SchoolController@update']);
        Route::patch('schools/{schools}', ['as' => 'admin.schools.update', 'uses' => 'Admin\SchoolController@update']);
        Route::delete('schools/{schools}', ['as' => 'admin.schools.destroy', 'uses' => 'Admin\SchoolController@destroy']);
        Route::get('schools/{schools}', ['as' => 'admin.schools.show', 'uses' => 'Admin\SchoolController@show']);
        Route::get('schools/{schools}/edit', ['as' => 'admin.schools.edit', 'uses' => 'Admin\SchoolController@edit']);

        //fam_tours
        Route::get('fam_tours', ['as' => 'admin.fam_tours.index', 'uses' => 'Admin\FamTourController@index'])->middleware('filterRequest');
        Route::put('fam_tours', ['as' => 'admin.fam_tours.update', 'uses' => 'Admin\FamTourController@update']);
        Route::patch('fam_tours', ['as' => 'admin.fam_tours.update', 'uses' => 'Admin\FamTourController@update']);
        Route::post('fam_tours/sync', ['as' => 'admin.fam_tours.sync', 'uses' => 'Admin\FamTourController@sync']);
        Route::get('fam_tours/{fam_tours}', ['as' => 'admin.fam_tours.show', 'uses' => 'Admin\FamTourController@show']);
        Route::get('fam_tours/{fam_tours}/edit', ['as' => 'admin.fam_tours.edit', 'uses' => 'Admin\FamTourController@edit']);

        //mercado-pago
        Route::get('mercado-pago', ['as' => 'admin.mercadoPagos.index', 'uses' => 'Admin\MercadoPagoController@index']);
        Route::get('mercado-pago/{mercadoPagos}', ['as' => 'admin.mercadoPagos.show', 'uses' => 'Admin\MercadoPagoController@show']);


        //Group IDG
        Route::group(['prefix' => 'idg'], function () {
            //MediaInternalOrders
            Route::get('media_invoices', ['as' => 'idg.media_invoices.index', 'uses' => 'Admin\IDG\MediaInvoiceController@index'])->middleware('filterRequest');
            Route::get('media_invoices/category/{id}', ['as' => 'idg.media_invoices.category', 'uses' => 'Admin\IDG\MediaInvoiceController@category']);
            Route::post('media_invoices/update_item', ['as' => 'idg.media_invoices.update_item', 'uses' => 'Admin\IDG\MediaInvoiceController@update_item']);
            Route::post('media_invoices/update_mass_item', ['as' => 'idg.media_invoices.update_mass_item', 'uses' => 'Admin\IDG\MediaInvoiceController@update_mass_item']);
            Route::get('media_invoices/create', ['as' => 'idg.media_invoices.create', 'uses' => 'Admin\IDG\MediaInvoiceController@create']);
            Route::get('media_invoices/confirm/{media_invoices}', ['as' => 'idg.media_invoices.confirm', 'uses' => 'Admin\IDG\MediaInvoiceController@confirm']);
            Route::get('media_invoices/{media_invoices}', ['as' => 'idg.media_invoices.show', 'uses' => 'Admin\IDG\MediaInvoiceController@show']);
            Route::delete('media_invoices/{media_invoices}', ['as' => 'idg.media_invoices.destroy', 'uses' => 'Admin\IDG\MediaInvoiceController@destroy']);

            Route::get('media_invoices/payment/{media_invoices}', ['as' => 'idg.media_invoices.payment', 'uses' => 'Admin\IDG\MediaInvoiceController@payment']);
            Route::post('media_invoices/send_payment/{media_invoices}', ['as' => 'idg.media_invoices.sendPayment', 'uses' => 'Admin\IDG\MediaInvoiceController@sendPayment']);
            Route::post('media_invoices/select_payment/{media_invoices}', ['as' => 'idg.media_invoices.selectPayment', 'uses' => 'Admin\IDG\MediaInvoiceController@selectPayment']);
            Route::get('media_invoices/credit_card/{media_invoices}', ['as' => 'idg.media_invoices.creditCard', 'uses' => 'Admin\IDG\MediaInvoiceController@creditCard']);
            Route::post('media_invoices/card_payment/{media_invoices}', ['as' => 'idg.media_invoices.cardPayment', 'uses' => 'Admin\IDG\MediaInvoiceController@cardPayment']);

            //InternalOrders
            Route::get('invoices', ['as' => 'idg.invoices.index', 'uses' => 'Admin\IDG\InvoiceController@index'])->middleware('filterRequest');
            Route::get('invoices/create', ['as' => 'idg.invoices.create', 'uses' => 'Admin\IDG\InvoiceController@create']);
            Route::get('invoices/{id}/edit', ['as' => 'idg.invoices.edit', 'uses' => 'Admin\IDG\InvoiceController@edit']);
            Route::get('invoices/{id}/print', ['as' => 'idg.invoices.print', 'uses' => 'Admin\IDG\InvoiceController@print']);
            Route::get('invoices/{id}/confirm', ['as' => 'idg.invoices.confirm', 'uses' => 'Admin\IDG\InvoiceController@confirm']);
            Route::put('invoices/{id}', ['as' => 'idg.invoices.update', 'uses' => 'Admin\IDG\InvoiceController@update']);
            Route::patch('invoices/{id}', ['as' => 'idg.invoices.update', 'uses' => 'Admin\IDG\InvoiceController@update']);
            Route::put('invoices/{id}/confirm_invoice', ['as' => 'idg.invoices.confirm_invoice', 'uses' => 'Admin\IDG\InvoiceController@confirm_invoice']);
            Route::patch('invoices/{id}/confirm_invoice', ['as' => 'idg.invoices.confirm_invoice', 'uses' => 'Admin\IDG\InvoiceController@confirm_invoice']);
            Route::post('invoices', ['as' => 'idg.invoices.store', 'uses' => 'Admin\IDG\InvoiceController@store']);
            Route::get('invoices/{invoices}', ['as' => 'idg.invoices.show', 'uses' => 'Admin\IDG\InvoiceController@show']);
            Route::delete('invoices/{invoices}', ['as' => 'idg.invoices.destroy', 'uses' => 'Admin\IDG\InvoiceController@destroy']);


            Route::get('products', ['as' => 'idg.products.index', 'uses' => 'Admin\IDG\ProductController@index'])->middleware('filterRequest');
            Route::post('products', ['as' => 'idg.products.store', 'uses' => 'Admin\IDG\ProductController@store']);
            Route::get('products/create', ['as' => 'idg.products.create', 'uses' => 'Admin\IDG\ProductController@create']);
            Route::put('products/{products}', ['as' => 'idg.products.update', 'uses' => 'Admin\IDG\ProductController@update']);
            Route::patch('products/{products}', ['as' => 'idg.products.update', 'uses' => 'Admin\IDG\ProductController@update']);
            Route::delete('products/{products}', ['as' => 'idg.products.destroy', 'uses' => 'Admin\IDG\ProductController@destroy']);
            Route::get('products/{products}', ['as' => 'idg.products.show', 'uses' => 'Admin\IDG\ProductController@show']);
            Route::get('products/{products}/edit', ['as' => 'idg.products.edit', 'uses' => 'Admin\IDG\ProductController@edit']);

            Route::get('products/{products}/items', ['as' => 'idg.products.items', 'uses' => 'Admin\IDG\ProductController@items']);
            Route::put('products/{products}/updateItems', ['as' => 'idg.products.updateItems', 'uses' => 'Admin\IDG\ProductController@updateItems']);
            Route::patch('products/{products}/updateItems', ['as' => 'idg.products.updateItems', 'uses' => 'Admin\IDG\ProductController@updateItems']);

            Route::get('categories', ['as' => 'idg.categories.index', 'uses' => 'Admin\IDG\CategoryController@index'])->middleware('filterRequest');
            Route::post('categories', ['as' => 'idg.categories.store', 'uses' => 'Admin\IDG\CategoryController@store']);
            Route::get('categories/create', ['as' => 'idg.categories.create', 'uses' => 'Admin\IDG\CategoryController@create']);
            Route::put('categories/{categories}', ['as' => 'idg.categories.update', 'uses' => 'Admin\IDG\CategoryController@update']);
            Route::patch('categories/{categories}', ['as' => 'idg.categories.update', 'uses' => 'Admin\IDG\CategoryController@update']);
            Route::delete('categories/{categories}', ['as' => 'idg.categories.destroy', 'uses' => 'Admin\IDG\CategoryController@destroy']);
            Route::get('categories/{categories}', ['as' => 'idg.categories.show', 'uses' => 'Admin\IDG\CategoryController@show']);
            Route::get('categories/{categories}/edit', ['as' => 'idg.categories.edit', 'uses' => 'Admin\IDG\CategoryController@edit']);
        });

    });
    //Settings
    Route::get('settings', ['as' => 'admin.settings.index', 'uses' => 'Admin\SettingController@index'])->middleware('filterRequest');
    Route::put('settings', ['as' => 'admin.settings.update', 'uses' => 'Admin\SettingController@update']);
    Route::patch('settings', ['as' => 'admin.settings.update', 'uses' => 'Admin\SettingController@update']);
    //User Verifications
    Route::get('user-verifications', ['as' => 'admin.userVerifications.index', 'uses' => 'Admin\UserVerificationController@index'])->middleware('filterRequest');
});
