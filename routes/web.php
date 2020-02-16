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

Route::group(['prefix'  => 'operator/manajemen_skim'],function(){
    Route::get('/','Operator\SkimController@index')->name('operator.skim');
    Route::post('/','Operator\SkimController@post')->name('operator.skim.add');
    Route::get('/{id}/edit','Operator\SkimController@edit')->name('operator.skim.edit');
    Route::patch('/','Operator\SkimController@update')->name('operator.skim.update');
    Route::delete('/','Operator\SkimController@delete')->name('operator.skim.delete');
});

Route::group(['prefix'  => 'operator/manajemen_prodi'],function(){
    Route::get('/','Operator\ProdiController@index')->name('operator.prodi');
    Route::get('/get_data_prodi','Operator\ProdiController@getDataProdi')->name('operator.get_data_prodi');
});

Route::group(['prefix'  => 'operator/manajemen_fakultas'],function(){
    Route::get('/','Operator\FakultasController@index')->name('operator.fakultas');
    Route::get('/get_data_fakultas','Operator\FakultasController@getDataFakultas')->name('operator.get_data_fakultas');
});

Route::group(['prefix'  => 'operator/bidang_penelitian'],function(){
    Route::get('/','Operator\BidangPenelitianController@index')->name('operator.bidang');
    Route::post('/','Operator\BidangPenelitianController@post')->name('operator.bidang.add');
    Route::get('/{id}/edit','Operator\BidangPenelitianController@edit')->name('operator.bidang.edit');
    Route::patch('/','Operator\BidangPenelitianController@update')->name('operator.bidang.update');
    Route::delete('/','Operator\BidangPenelitianController@delete')->name('operator.bidang.delete');
});

Route::group(['prefix'  => 'operator/variabel_penilaian'],function(){
    Route::get('/','Operator\VariabelPenilaianController@index')->name('operator.variabel_penilaian');
    Route::post('/','Operator\VariabelPenilaianController@post')->name('operator.variabel_penilaian.add');
    Route::get('/{id}/edit','Operator\VariabelPenilaianController@edit')->name('operator.variabel_penilaian.edit');
    Route::patch('/','Operator\VariabelPenilaianController@update')->name('operator.variabel_penilaian.update');
    Route::delete('/','Operator\VariabelPenilaianController@delete')->name('operator.variabel_penilaian.delete');
});

Route::group(['prefix'  => 'operator/usulan_dosen/menunggu_disetujui'],function(){
    Route::get('/','Operator\UsulanMenungguController@index')->name('operator.menunggu');
    Route::get('/{id}/detail','Operator\UsulanMenungguController@detail')->name('operator.menunggu.deail');
    Route::post('/reviewer','Operator\UsulanMenungguController@reviewerPost')->name('operator.usulan.reviewer_post');
    Route::get('/{id}/get_reviewer','Operator\UsulanMenungguController@getReviewer')->name('operator.usulan.get_reviewer');
    Route::get('/cari_prodi','Operator\UsulanMenungguController@cariProdi')->name('operator.usulan.cari_prodi');
    Route::get('/cari_reviewer','Operator\UsulanMenungguController@cariReviewer')->name('operator.usulan.cari_reviewer');
    Route::get('/anggaran/{id}/cetak','Operator\UsulanMenungguController@anggaranCetak')->name('operator.usulan.anggaran.cetak');
});

Route::group(['prefix'  => 'operator/usulan_dosen/proses_review'],function(){
    Route::get('/','Operator\UsulanProsesReviewController@index')->name('operator.proses_review');
    Route::get('/{id}/detail','Operator\UsulanProsesReviewController@detail')->name('operator.proses_review.deail');
    Route::get('/{id}/get_reviewer','Operator\UsulanProsesReviewController@getReviewer')->name('operator.usulan.get_reviewer');
    Route::get('/anggaran/{id}/cetak','Operator\UsulanProsesReviewController@anggaranCetak')->name('operator.usulan.anggaran.cetak');
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
    Route::post('/anggota','Pengusul\UsulanController@anggotaPost')->name('pengusul.usulan.anggota_post');
    Route::get('/{id}/get_anggota','Pengusul\UsulanController@getAnggota')->name('pengusul.usulan.get_anggota');
    Route::get('/cari_prodi','Pengusul\UsulanController@cariProdi')->name('admin.pembahasan.cari_prodi');
    Route::get('/cari_anggota','Pengusul\UsulanController@cariAnggota')->name('admin.pembahasan.cari_anggota');
    Route::patch('/usulkan','Pengusul\UsulanController@usulkan')->name('pengusul.usulan.usulkan');
    Route::post('/anggaran','Pengusul\UsulanController@anggaranPost')->name('pengusul.usulan.anggaran_post');
    Route::get('/anggaran/{id}/cetak','Pengusul\UsulanController@anggaranCetak')->name('pengusul.usulan.anggaran.cetak');
    Route::get('/','Pengusul\UsulanController@index')->name('pengusul.usulan');
    Route::get('/{id}/detail_anggaran','Pengusul\UsulanController@detailAnggaran')->name('pengusul.usulan.detail_anggaran');
    Route::get('/{id}/get_anggaran','Pengusul\UsulanController@getAnggaran')->name('pengusul.usulan.get_anggaran');

});

Route::group(['prefix'  => 'reviewer/'],function(){
    Route::get('/login','UserLoginController@showReviewerLoginForm')->name('panda.reviewer_login.form');
    Route::post('/pandaloginreviewer','UserLoginController@pandaLoginReviewer')->name('panda.login.reviewer');
    Route::get('/user_logout','UserLoginController@reviewerLogout')->name('logout_reviewer');
    Route::get('/','Reviewer\DashboardController@index')->name('reviewer.dashboard');
});

Route::group(['prefix'  => 'reviewer/usulan_dosen/menunggu_disetujui'],function(){
    Route::get('/','Reviewer\UsulanMenungguController@index')->name('reviewer.menunggu');
    Route::get('/{id}/detail','Reviewer\UsulanMenungguController@detail')->name('reviewer.menunggu.detail');
    Route::get('/anggaran/{id}/cetak','Reviewer\UsulanMenungguController@anggaranCetak')->name('reviewer.usulan.anggaran.cetak');
    Route::get('/{id}/review','Reviewer\UsulanMenungguController@review')->name('reviewer.usulan.review');
});
