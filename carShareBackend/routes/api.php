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
    // Route::post('create', 'PasswordResetController@create');
    // Route::get('find/{token}', 'PasswordResetController@find');
    // Route::post('reset', 'PasswordResetController@reset');
});
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('bookings/customer/{id}', 'BookingController@showBookingByCusId');

Route::get('bookings/status/{id}/{status}', 'BookingController@showMyBookings');

Route::put('bookings/paymentStatus/{id}/{status}', 'BookingController@changePaymentStatus');


Route::get('bookings/status/{status}', 'BookingController@showBookingsByStatus');

Route::get('bookings/cars/unavailableDates/{id}', 'BookingController@showCarsUnavailableDates');

Route::put('bookings/status/activate/{status}', 'BookingController@activateBooking');

Route::put('bookings/status/deactivate/{status}', 'BookingController@deactivateBooking');

Route::put('users/activate/{id}', 'CustomerController@activateCustomer');
Route::get('users/customers/inactive', 'CustomerController@showInactiveCustomers');
Route::get('users/customers/active', 'CustomerController@showActiveCustomers');
Route::get('cars/availability/{availability}', 'CarController@getByAvailability');
Route::get('cars/testImg', 'CarController@testImg');
Route::post('cars/storeTest', 'CarController@storeTest2');

Route::get('/payment/execute', 'PaymentController@execute');
Route::get('/payment/create', 'PaymentController@createPayment');
Route::get('/payment/determineBookingStatus', 'PaymentController@determineBookingStatus');

Route::resource('bookings', 'BookingController');
Route::resource('customers', 'CustomerController');
Route::resource('cars', 'CarController');
Route::resource('histories', 'HistoryController');
Route::resource('locations', 'LocationController');
Route::resource('users', 'UserController');
Route::resource('pw', 'PasswordResetController');

Route::get('history/{id}', 'HistoryController@showMyHistories');

Route::post('password/create', 'PasswordResetController@create');
Route::get('password/find/{token}', 'PasswordResetController@find');
Route::post('password/reset', 'PasswordResetController@reset');
