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
    // if(Auth::check()){return redirect()->route('operator.dashboard');}
    return redirect()->route('panda.login.form');
});

// Auth::routes();

Route::get('/login','UserLoginController@showLoginForm')->name('panda.login.form');
Route::post('/pandalogin','UserLoginController@pandaLogin')->name('panda.login.dosen');
Route::get('/user_logout','UserLoginController@userLogout')->name('logout_user');

Route::group(['prefix'  => 'operator/'],function(){
    Route::get('/','Operator\DashboardController@index')->name('operator.dashboard');
    Auth::routes();
});

Route::group(['prefix'  => 'operator/manajemen_dosen'],function(){
    Route::get('/','Operator\DosenController@index')->name('operator.dosen');
    Route::get('/get_data_dosen','Operator\DosenController@getDataDosen')->name('operator.get_data_dosen');
    Route::get('/api','Operator\DosenController@dataTable')->name('operator.dosen_api');
});

Route::group(['prefix'  => 'operator/manajemen_operator'],function(){
    Route::get('/','Operator\OperatorController@index')->name('operator.operator');
    Route::post('/','Operator\OperatorController@post')->name('operator.operator.add');
    Route::get('/{id}/edit','Operator\OperatorController@edit')->name('operator.operator.edit');
    Route::patch('/','Operator\OperatorController@update')->name('operator.operator.update');
    Route::delete('/','Operator\OperatorController@delete')->name('operator.operator.delete');
    Route::post('/cari_email','Operator\OperatorController@cariEmail')->name('operator.operator.cari_email');
    Route::post('/cari_username','Operator\OperatorController@cariUsername')->name('operator.operator.cari_username');
    Route::patch('/aktifkan_status/{id}','Operator\OperatorController@aktifkanStatus')->name('manajer.operator.aktifkan_status');
    Route::patch('/nonaktifkan_status/{id}','Operator\OperatorController@nonAktifkanStatus')->name('operator.operator.nonaktifkan_status');
    Route::patch('/ubah_password','Operator\OperatorController@updatePassword')->name('operator.operator.update_password');
});

// Route Pengusul
Route::group(['prefix'  => 'pengusul/'],function(){
    Route::get('/','Pengusul\DashboardController@index')->name('pengusul.dashboard');
});

Route::group(['prefix'  => 'pengusul/manajemen_usulan'],function(){
    Route::get('/','Pengusul\UsulanController@index')->name('pengusul.usulan');
    Route::post('/','Pengusul\UsulanController@post')->name('pengusul.usulan.add');
    Route::get('/{id}/edit','Pengusul\UsulanController@edit')->name('pengusul.usulan.edit');
    Route::patch('/','Pengusul\UsulanController@update')->name('pengusul.usulan.update');
    Route::delete('/','Pengusul\UsulanController@delete')->name('pengusul.usulan.delete');
});
