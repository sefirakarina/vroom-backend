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
Route::group([
    //'middleware' => 'api',
    //'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('bookings/customer/{id}', 'BookingController@showBookingByCusId');
Route::put('users/activate/{id}', 'CustomerController@activateCustomer');
Route::get('users/customers/inactive', 'CustomerController@showInactiveCustomers');
Route::get('users/customers/active', 'CustomerController@showActiveCustomers');
Route::get('cars/availability/{availability}', 'CarController@getByAvailability');
Route::get('cars/testImg', 'CarController@testImg');
Route::post('cars/storeTest', 'CarController@storeTest2');

Route::resource('bookings', 'BookingController');
Route::resource('customers', 'CustomerController');
Route::resource('cars', 'CarController');
Route::resource('histories', 'HistoryController');
Route::resource('locations', 'LocationController');
Route::resource('users', 'UserController');
