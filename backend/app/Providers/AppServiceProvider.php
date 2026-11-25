<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use App\Models\Admin\Brand;
use App\Models\Admin\Package;
use App\Models\Store;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\Image;
use Optix\Media\Facades\Conversion;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        \Carbon\Carbon::setLocale(config('app.locale'));


        Conversion::register(Package::$IMAGE_CONVERSION_THUMBNAIL, function (Image $image) {
            return $image->fit(200, 200);
        });
        Conversion::register(Brand::$IMAGE_CONVERSION_THUMBNAIL, function (Image $image) {
            return $image->fit(300, 200);
        });
        Conversion::register(Store::$IMAGE_CONVERSION_ICON, function (Image $image) {
            return $image->resize(600, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        });
        Conversion::register(Package::$IMAGE_CONVERSION_DESKTOP, function (Image $image) {
            return $image->fit(1920, 1080);
        });
        Conversion::register(Package::$IMAGE_CONVERSION_MOBILE, function (Image $image) {
            return $image->fit(800, 800);
        });
        Conversion::register(Package::$IMAGE_CONVERSION_MULTIMEDIA_DESKTOP, function (Image $image) {
            return $image->fit(	1920, 1080 );
        });
        Conversion::register(Package::$IMAGE_CONVERSION_MULTIMEDIA_MOBILE, function (Image $image) {
            return $image->fit(	600 , 380);
        });

        
        Blade::component('components.filter', 'filter');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
