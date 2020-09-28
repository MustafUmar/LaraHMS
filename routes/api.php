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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('personnels', 'personnel\PersonnelController@personnels');

    Route::middleware('admin')->group(function() {
        Route::post('personnel/list', 'personnel\PersonnelController@list');
        Route::post('account/reset/{id}', 'personnel\PersonnelController@resetAccount');

        Route::get('doctors', 'personnel\DoctorController@doctors');
        Route::get('doctor/{id}', 'personnel\DoctorController@view');
        Route::post('doc/new', 'personnel\DoctorController@create');
        Route::post('doc/update/{id}', 'personnel\DoctorController@update');
        Route::get('doc/skills', 'personnel\DoctorController@skills');

        Route::get('receptions', 'personnel\ReceptionController@index');
        Route::post('reception/new', 'personnel\ReceptionController@create');
        Route::post('reception/update/{id}', 'personnel\ReceptionController@update');
        Route::get('reception/{id}', 'personnel\ReceptionController@view');

        Route::get('accounts', 'personnel\AccountController@accounts');
        Route::post('account/new', 'personnel\AccountController@create');
        Route::post('account/update/{id}', 'personnel\AccountController@update');
        Route::get('account/{id}', 'personnel\AccountController@view');

        Route::get('staffs', 'personnel\StaffController@index');
        Route::post('staff/new', 'personnel\StaffController@create');
        Route::post('staff/update/{id}', 'personnel\StaffController@update');
        Route::post('staff/swap/{id}', 'personnel\StaffController@changeRole');
        Route::get('staff/{id}', 'personnel\StaffController@view');

    });

    Route::middleware(['doctor'])->group(function() {

    });

    Route::middleware(['recep'])->group(function() {
        Route::post('patient/file/new', 'reception\PatientController@create');
        Route::get('patient/file/{id}', 'reception\PatientController@file');
        Route::get('patient/search', 'reception\PatientController@patients');
        Route::get('patient/info/{id}', 'reception\PatientController@patient');
    });

    Route::middleware(['account'])->group(function() {
        Route::post('payments', 'account\PaymentController@payments');
        Route::get('payment/recent', 'account\PaymentController@index');
        Route::get('payment/patients', 'account\PatientController@index');
        Route::get('payment/patient/search', 'account\PatientController@search');
        Route::get('payment/patient/{id}', 'account\PatientController@patient');
        Route::post('payment/bill/charge', 'account\PaymentController@charge');
        Route::get('payment/bill/{id}', 'account\PaymentController@payment');
    });
});
