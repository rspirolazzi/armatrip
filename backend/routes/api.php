<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['jwt.auth', 'cors']], function () {
    Route::post('precheckout', 'OrderAPIController@precheckout');
    Route::post('checkout', 'OrderAPIController@checkout');
    Route::post('payment', 'OrderAPIController@payment');
    Route::post('package/detail', 'PackageAPIController@detail');
    Route::post('user', 'ApiController@getAuthUser');
    Route::post('registerWithToken', 'ApiController@register');
    Route::post('coupons/check', 'CouponAPIController@check');
    Route::post('coupons/apply', 'CouponAPIController@apply');
    Route::post('coupons/remove', 'CouponAPIController@remove');
});

Route::group(['middleware' => 'cors'], function () {
    Route::post('register', 'ApiController@register');
    Route::get('brands', 'BrandAPIController@index');
    Route::get('provinces', 'ProvinceAPIController@index');
    Route::get('provinces/{store_code}', 'ProvinceAPIController@store');
    Route::post('continue_with_user', 'ApiController@continueWithUser');
    Route::get('verify/{verification_code}', 'ApiController@verifyUser');
    Route::post('sms/verify', 'ApiController@verifyUserBySMS');
    Route::post('sms/activation', 'ApiController@reSendSMS');

    Route::get('stores', 'StoreAPIController@index');
    Route::get('schools/{term?}', 'SchoolAPIController@index');
    Route::get('products', 'ProductAPIController@index');
    Route::get('products/{code}', 'ProductAPIController@show');

    Route::get('packages/{store_code}', 'PackageAPIController@index');
    //Route::get('package/{code}', 'PackageAPIController@show');

    Route::post('contact/country', 'ContactAPIController@storeContact');
    Route::get('notifications', 'NotificationAPIController@index');
    Route::post('notifications/uri', 'NotificationAPIController@byUri');

    Route::post('log/save', 'ApiController@log');
    Route::get('config', 'ApiController@config');
    Route::get('order/show/{code}', 'OrderAPIController@show');
});
