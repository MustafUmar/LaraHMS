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

Route::get('/', function () {
    return view('home');
});

Route::get('hpl/login', 'personnel\AdminController@showLoginForm');
Route::post('hpl/login', 'personnel\AdminController@login');
Route::post('hpl/logout', 'personnel\AdminController@logout');

Route::get('hpl/{dashboard}','personnel\PersonnelController@index')->where('dashboard','(.*)');
Route::get('hpl','personnel\PersonnelController@index');

Route::post('access', 'personnel\PersonnelController@verifyAccess');
Route::post('account/verify', 'personnel\PersonnelController@verifyUser');
Route::get('activate', 'personnel\PersonnelController@showActivateForm');
Route::post('activate', 'personnel\PersonnelController@activate');
Route::get('account/inactive', 'personnel\PersonnelController@accountInactive');
Route::get('account/notverifed/{ec}', 'personnel\PersonnelController@accountNotVerified');

// Route::middleware(['isreset'])->group(function() {

// });

// Route::post('verify/user', 'personnel\PersonnelController@verifyUser');
// Route::group(['middleware' => ['auth']], function () {

// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('404', function() { return view('404'); });
