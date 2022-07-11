<?php

use Illuminate\Support\Facades\Route;
// Rutas del usuario, login, registro y cierre de sesion
Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
Route::post('register/google', 'Api\AuthController@registerGoogle');
Route::post('login/google', 'Api\AuthController@loginGoogle');
Route::get('logout', 'Api\AuthController@logout');

Route::post('email', 'Api\AuthController@updateEmail');
Route::post('/ip/{station_id}', 'Api\AuthController@uploadIPStation');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

Route::group(['middleware' => 'jwtAuth'], function () {
    // Rutas Ver y Editar perfil de Usuario
    Route::get('profile', 'Api\UserController@index');
    Route::post('profile/update', 'Api\UserController@update');
    //Rutas para los usuarios tipo cliente
    Route::get('main', 'Api\ClientController@index');
    Route::get('balance', 'Api\ClientController@getListStations');
    Route::get('balance/history', 'Api\ClientController@history');
    Route::post('code', 'Api\ClientController@code');
    // Rutas para los abonos
    Route::get('payments', 'Api\BalanceController@getDeposits');
    Route::post('balance/pay', 'Api\BalanceController@addBalance');
    Route::get('balance/use', 'Api\BalanceController@useBalance');
    Route::post('balance/contact/sendbalance', 'Api\BalanceController@sendBalance');
    Route::get('balance/getlistreceived', 'Api\BalanceController@listReceivedPayments');
    Route::get('balance/getlistreceived/use', 'Api\BalanceController@useSharedBalance');
    Route::post('balance/makepayment', 'Api\BalanceController@makePayment');
    Route::post('points', 'Api\BalanceController@addPoints');
    Route::post('exchange', 'Api\BalanceController@exchange');
    //Rutas para contactos
    // Route::get('balance/contact/getlist', 'Api\ContactController@getListContacts');
    // Route::get('balance/contact', 'Api\ContactController@lookingForContact');
    // Route::post('balance/contact/add', 'Api\ContactController@addContact');
    // Route::post('balance/contact/delete', 'Api\ContactController@deleteContact');
});
// Rutal para el usuario con rol despachador
/* Route::group(['middleware' => 'jwtAuth'], function () {
    Route::get('maindispatcher', 'Api\DispatcherController@index');
    Route::get('gasolinelist', 'Api\DispatcherController@gasolineList');
    Route::post('notification', 'Api\DispatcherController@makeNotification');
    Route::get('getpaymentsnow', 'Api\DispatcherController@getPaymentsNow');
    Route::get('getschedules', 'Api\DispatcherController@getListSchedules');
    Route::get('getlistpayments', 'Api\DispatcherController@getListPayments');
    Route::post('time', 'Api\DispatcherController@startEndTime');
    Route::get('getsale', 'Api\DispatcherController@getSale');
}); */
