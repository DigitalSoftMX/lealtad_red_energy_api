<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;

use function Google\Auth\Cache\get;

// Route::get('sumardoble','Api\BalanceController@sumar');
// Route::get('/', function () {
// 	return view('auth.loginV0');
// });
// Route::get('/errors', function(){
//     return response()->view('errors.errors',['message'=>'La pagina no existe','status'=>419]);
// });

Route::get('/', function(){
    return response()->view('welcome');
});
// Route::get('/dash', 'Web\DashboardController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
