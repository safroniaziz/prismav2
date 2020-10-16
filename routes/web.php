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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // if(Auth::check()){return redirect()->route('operator.dashboard');}
    return redirect()->route('panda.login.form');
});

Route::get('/reviewer_eksternal', function () {
    // if(Auth::check()){return redirect()->route('operator.dashboard');}
    return redirect()->route('reviewer_usulan.dashboard');
});

// Auth::routes();

Route::get('/login','UserLoginController@showLoginForm')->name('panda.login.form');
Route::post('/pandalogin','UserLoginController@pandaLogin')->name('panda.login.dosen');
Route::get('/user_logout','UserLoginController@userLogout')->name('logout_user');

Route::group(['prefix'  => 'reviewer_eksternal/usulan'],function(){
    Route::get('/login','AuthReviewerUsulan\LoginController@showLoginForm')->name('reviewer_usulan.login');
    Route::post('/login','AuthReviewerUsulan\LoginController@login')->name('reviewer_usulan.login.submit');
    Route::get('/','ReviewerEksternal\ReviewerUsulanController@dashboard')->name('reviewer_usulan.dashboard');
});

Route::group(['prefix'  => 'reviewer_eksternal/usulan/menunggu_disetujui'],function(){
    Route::get('/','ReviewerEksternal\UsulanMenungguController@index')->name('reviewer_usulan.menunggu');
    Route::get('/{id}/detail/{slug}','ReviewerEksternal\UsulanMenungguController@detail')->name('reviewer_eksternal.menunggu.detail');
    Route::get('/{id}/detail','ReviewerEksternal\UsulanMenungguController@detail')->name('reviewer_usulan.menunggu.detail');
    Route::get('/anggaran/{id}/cetak','ReviewerEksternal\UsulanMenungguController@anggaranCetak')->name('reviewer_usulan.usulan.anggaran.cetak');
    Route::get('/review/{id}/{skim_id}/{kegiatan}/{slug}','ReviewerEksternal\UsulanMenungguController@review')->name('reviewer_usulan.usulan.review');
    Route::post('/review','ReviewerEksternal\UsulanMenungguController@reviewPost')->name('reviewer_usulan.usulan.review_post');
    Route::get('/riwayat_review','ReviewerEksternal\UsulanMenungguController@riwayat')->name('reviewer_usulan.riwayat');
});

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
    Route::patch('/aktifkan_status/{id}','Operator\SkimController@aktifkanStatus')->name('operator.skim.aktifkan_status');
    Route::patch('/non_aktifkan_status/{id}','Operator\SkimController@nonAktifkanStatus')->name('operator.skim.non_aktifkan_status');
});

Route::group(['prefix'  => 'operator/jenis_publikasi'],function(){
    Route::get('/','Operator\PublikasiController@index')->name('operator.publikasi');
    Route::post('/','Operator\PublikasiController@post')->name('operator.publikasi.add');
    Route::get('/{id}/edit','Operator\PublikasiController@edit')->name('operator.publikasi.edit');
    Route::patch('/','Operator\PublikasiController@update')->name('operator.publikasi.update');
    Route::delete('/','Operator\PublikasiController@delete')->name('operator.publikasi.delete');
    Route::patch('/aktifkan_status/{id}','Operator\PublikasiController@aktifkanStatus')->name('operator.aktifkan_status');
    Route::patch('/non_aktifkan_status/{id}','Operator\PublikasiController@nonAktifkanStatus')->name('operator.non_aktifkan_status');
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

Route::group(['prefix'  => 'operator/kriteria_penilaian'],function(){
    Route::get('/','Operator\KriteriaPenilaianController@index')->name('operator.kriteria_penilaian');
    Route::post('/','Operator\KriteriaPenilaianController@post')->name('operator.kriteria_penilaian.add');
    Route::get('/{id}/edit','Operator\KriteriaPenilaianController@edit')->name('operator.kriteria_penilaian.edit');
    Route::patch('/','Operator\KriteriaPenilaianController@update')->name('operator.kriteria_penilaian.update');
    Route::delete('/','Operator\KriteriaPenilaianController@delete')->name('operator.kriteria_penilaian.delete');
});

Route::group(['prefix'  => 'operator/usulan_dosen/usulan_pending'],function(){
    Route::get('/','Operator\UsulanPendingController@index')->name('operator.pending');
    Route::get('/{id}/detail/{slug}','Operator\UsulanPendingController@detail')->name('operator.pending.detail');
    Route::get('/anggaran/{id}/cetak','Operator\UsulanPendingController@anggaranCetak')->name('operator.pending.anggaran.cetak');
    Route::get('/{id}/detail_reviewer','Operator\UsulanPendingController@detailReviewer')->name('operator.pending.detail_reviewer');
    Route::get('/cetak','Operator\UsulanPendingController@cetak')->name('operator.pending.cetak');
    Route::get('/cetak_pengabdian','Operator\UsulanPendingController@cetakPengabdian')->name('operator.pending.cetak_pengabdian');
});

Route::group(['prefix'  => 'operator/usulan_dosen/menunggu_disetujui'],function(){
    Route::get('/','Operator\UsulanMenungguController@index')->name('operator.menunggu');
    Route::get('/{id}/detail/{slug}','Operator\UsulanMenungguController@detail')->name('operator.menunggu.detail');
    Route::post('/reviewer','Operator\UsulanMenungguController@reviewerPost')->name('operator.usulan.reviewer_post');
    Route::post('/reviewer_eksternal','Operator\UsulanMenungguController@reviewerEksternalPost')->name('operator.usulan.reviewer_eksternal_post');
    Route::get('/{id}/get_reviewer','Operator\UsulanMenungguController@getReviewer')->name('operator.usulan.get_reviewer');
    Route::get('/anggaran/{id}/cetak','Operator\UsulanMenungguController@anggaranCetak')->name('operator.usulan.anggaran.cetak');
    Route::get('/cetak','Operator\UsulanMenungguController@cetak')->name('operator.menunggu.cetak');
    Route::get('/cetak_pengabdian','Operator\UsulanMenungguController@cetakPengabdian')->name('operator.menunggu.cetak_pengabdian');

    Route::get('/{id}/detail_reviewer','Operator\UsulanMenungguController@detailReviewer')->name('operator.menunggu.detail_reviewer');
    Route::get('/cari_reviewer','Operator\UsulanMenungguController@cariReviewer')->name('operator.menunggu.cari_reviewer');
    Route::delete('/detail_reviewer/{id}/hapus','Operator\UsulanMenungguController@hapusReviewer')->name('operator.menunggu.detail_reviewer.hapus');
    Route::delete('/detail_reviewer_eksternal','Operator\UsulanMenungguController@hapusReviewerEksternal')->name('operator.menunggu.detail_reviewer_eksternal.hapus');
    Route::patch('/batalkan/{id}','Operator\UsulanMenungguController@batalkan')->name('operator.menunggu.batalkan');


});

Route::group(['prefix'  => 'operator/usulan_dosen/laporan_kemajuan'],function(){
    Route::get('/','Operator\LaporanKemajuanController@index')->name('operator.laporan_kemajuan');
    Route::get('/{id}/detail','Operator\LaporanKemajuanController@detail')->name('operator.laporan_kemajuan.deail');
    Route::get('/{id}/get_reviewer','Operator\LaporanKemajuanController@getReviewer')->name('operator.laporan_kemajuan.get_reviewer');
    Route::get('/{id}/detail_judul','Operator\LaporanKemajuanController@detailJudul')->name('operator.laporan_kemajuan.detail_judul');

    Route::get('/{id}/detail_reviewer','Operator\LaporanKemajuanController@detailReviewer')->name('operator.laporan_kemajuan.detail_reviewer');
    Route::get('/cari_reviewer','Operator\LaporanKemajuanController@cariReviewer')->name('operator.laporan_kemajuan.cari_reviewer');
    Route::delete('/detail_reviewer','Operator\LaporanKemajuanController@hapusReviewer')->name('operator.laporan_kemajuan.detail_reviewer.hapus');
    Route::post('/reviewer','Operator\LaporanKemajuanController@reviewerPost')->name('operator.laporan_kemajuan.reviewer_post');
    Route::post('/reviewer_eksternal','Operator\LaporanKemajuanController@reviewerEksternalPost')->name('operator.laporan_kemajuan.reviewer_eksternal_post');
    Route::delete('/detail_reviewer_eksternal','Operator\LaporanKemajuanController@hapusReviewerEksternal')->name('operator.laporan_kemajuan.detail_reviewer_eksternal.hapus');


});

Route::group(['prefix'  => 'operator/usulan_dosen/laporan_kemajuan/proses_review'],function(){
    Route::get('/','Operator\LaporanKemajuanProsesReview@index')->name('operator.laporan_kemajuan.proses_review');
    Route::get('/{id}/detail','Operator\LaporanKemajuanProsesReview@detail')->name('operator.laporan_kemajuan.proses_review.detail');
    Route::get('/{id}/get_reviewer','Operator\LaporanKemajuanProsesReview@getReviewer')->name('operator.usulan.get_reviewer');
    Route::get('/anggaran/{id}/cetak','Operator\LaporanKemajuanProsesReview@anggaranCetak')->name('operator.usulan.anggaran.cetak');
});

Route::group(['prefix'  => 'operator/usulan_dosen/proses_review'],function(){
    Route::get('/','Operator\UsulanProsesReviewController@index')->name('operator.proses_review');
    Route::get('/{id}/detail/{slug}','Operator\UsulanProsesReviewController@detail')->name('operator.proses_review.detail');
    Route::get('/{id}/get_reviewer','Operator\UsulanProsesReviewController@getReviewer')->name('operator.usulan.get_reviewer');
    Route::get('/anggaran/{id}/cetak','Operator\UsulanProsesReviewController@anggaranCetak')->name('operator.usulan.anggaran.cetak');

    Route::get('/{id}/detail_reviewer','Operator\UsulanProsesReviewController@detailReviewer')->name('operator.proses_review.detail_reviewer');
    Route::get('/cari_reviewer','Operator\UsulanProsesReviewController@cariReviewer')->name('operator.proses_review.cari_reviewer');
    Route::delete('/detail_reviewer','Operator\UsulanProsesReviewController@hapusReviewer')->name('operator.proses_review.detail_reviewer.hapus');
    Route::delete('/detail_reviewer_eksternal','Operator\UsulanProsesReviewController@hapusReviewerEksternal')->name('operator.proses_review.detail_reviewer_eksternal.hapus');

    Route::post('/reviewer','Operator\UsulanProsesReviewController@reviewerPost')->name('operator.proses_review.reviewer_post');
    Route::post('/reviewer_eksternal','Operator\UsulanProsesReviewController@reviewerEksternalPost')->name('operator.proses_review.reviewer_eksternal_post');
    Route::get('/{id}/get_reviewer','Operator\UsulanProsesReviewController@getReviewer')->name('operator.proses_review.get_reviewer');

    Route::get('/cetak','Operator\UsulanProsesReviewController@cetak')->name('operator.proses_review.cetak');
    Route::get('/cetak_pengabdian','Operator\UsulanProsesReviewController@cetakPengabdian')->name('operator.proses_review.cetak_pengabdian');
});

Route::group(['prefix'  => 'operator/usulan_dosen/menunggu_verifikasi'],function(){
    Route::get('/','Operator\VerifikasiUsulanController@index')->name('operator.verifikasi');
    Route::get('/{id}/get_reviewer','Operator\VerifikasiUsulanController@getReviewer')->name('operator.verifikasi.get_reviewer');
    Route::get('/anggaran/{id}/cetak','Operator\VerifikasiUsulanController@anggaranCetak')->name('operator.verifikasi.anggaran.cetak');
    Route::patch('/verifikasi','Operator\VerifikasiUsulanController@verifikasi')->name('operator.verifikasi.verifikasi');
    Route::get('/{id}/detail','Operator\VerifikasiUsulanController@detail')->name('operator.verifikasi.detail');
    Route::get('/{id}/detail_reviewer','Operator\VerifikasiUsulanController@detailReviewer')->name('operator.verifikasi.detail_reviewer');
    Route::get('/{id}/detail_judul','Operator\VerifikasiUsulanController@detailJudul')->name('operator.verifikasi.detail_judul');
    Route::get('/{id}/komentar','Operator\VerifikasiUsulanController@komentar')->name('operator.verifikasi.komentar');
    Route::get('/{id}/{skim_id}/reviewer3/{slug}','Operator\VerifikasiUsulanController@reviewerTiga')->name('operator.verifikasi.reviewer3');
    Route::get('/detail_penilaian/{id}/{skim_id}/{slug}','Operator\VerifikasiUsulanController@detailPenilaian')->name('operator.verifikasi.detail_penilaian');
    Route::post('reviewer3','Operator\VerifikasiUsulanController@reviewerTigaPost')->name('operator.verifikasi.reviewer3_post');
    Route::patch('/verifikasi/{id}','Operator\VerifikasiUsulanController@updateBiaya')->name('operator.verifikasi.update_biaya');

    Route::get('/cetak','Operator\VerifikasiUsulanController@cetak')->name('operator.verifikasi.cetak');
    Route::get('/cetak_pengabdian','Operator\VerifikasiUsulanController@cetakPengabdian')->name('operator.verifikasi.cetak_pengabdian');
});

Route::group(['prefix'  => 'operator/usulan_dosen/laporan_kemajuan/menunggu_verifikasi'],function(){
    Route::get('/','Operator\VerifikasiLaporanKemajuanController@index')->name('operator.laporan_kemajuan.verifikasi');
    Route::get('/{id}/get_reviewer','Operator\VerifikasiLaporanKemajuanController@getReviewer')->name('operator.laporan_kemajuan.verifikasi.get_reviewer');
    Route::get('/anggaran/{id}/cetak','Operator\VerifikasiLaporanKemajuanController@anggaranCetak')->name('operator.laporan_kemajuan.verifikasi.anggaran.cetak');
    Route::patch('/verifikasi','Operator\VerifikasiLaporanKemajuanController@verifikasi')->name('operator.laporan_kemajuan.verifikasi.verifikasi');
    Route::get('/{id}/detail','Operator\VerifikasiLaporanKemajuanController@detail')->name('operator.laporan_kemajuan.verifikasi.detail');
    Route::get('/{id}/detail_judul','Operator\VerifikasiLaporanKemajuanController@detailJudul')->name('operator.laporan_kemajuan.detail_judul');
    Route::get('/{id}/komentar','Operator\VerifikasiLaporanKemajuanController@komentar')->name('operator.laporan_kemajuan.komentar');
});

Route::group(['prefix'  => 'operator/usulan_dosen/hasil_verifikasi'],function(){
    Route::get('/diterima','Operator\HasilUsulanController@diterima')->name('operator.diterima');
    Route::get('/ditolak','Operator\HasilUsulanController@ditolak')->name('operator.ditolak');
});

Route::group(['prefix'  => 'operator/usulan_dosen/laporan_kemajuan/hasil_verifikasi'],function(){
    Route::get('/diterima','Operator\HasilLaporanKemajuanController@diterima')->name('operator.laporan_kemajuan_diterima');
    Route::get('/ditolak','Operator\HasilLaporanKemajuanController@ditolak')->name('operator.laporan_kemajuan_ditolak');
});

Route::group(['prefix'  => 'operator/usulan_dosen/laporan_akhir'],function(){
    Route::get('/','Operator\LaporanAkhirController@index')->name('operator.laporan_akhir');
    Route::get('/filter','Operator\LaporanAkhirController@filter')->name('operator.laporan_akhir.filter');
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

Route::group(['prefix'  => 'operator/manajemen_reviewer'],function(){
    Route::get('/','Operator\ReviewerController@index')->name('operator.reviewer');
    Route::get('/tambah_reviewer','Operator\ReviewerController@add')->name('operator.reviewer.add');
    Route::get('/cari_prodi','Operator\ReviewerController@cariProdi')->name('operator.reviewer.cari_prodi');
    Route::post('/','Operator\ReviewerController@post')->name('operator.reviewer.post');
    Route::get('/{id}/edit','Operator\ReviewerController@edit')->name('operator.reviewer.edit');
    Route::patch('/','Operator\ReviewerController@update')->name('operator.reviewer.update');
    Route::delete('/','Operator\ReviewerController@delete')->name('operator.reviewer.delete');
    Route::patch('/update_password','Operator\ReviewerController@updatePassword')->name('operator.reviewer.update_password');
    Route::patch('/nonaktifkan_status/{id}','Operator\ReviewerController@nonAktifkanStatus')->name('operator.reviewer.nonaktifkan_status');
    Route::patch('/aktifkan_status/{id}','Operator\ReviewerController@aktifkanStatus')->name('operator.reviewer.aktifkan_status');
});

Route::group(['prefix'  => 'operator/manajemen_reviewer_eksternal'],function(){
    Route::get('/','Operator\ReviewerEksternalController@index')->name('operator.reviewer_eksternal');
    Route::get('/tambah_reviewer','Operator\ReviewerEksternalController@add')->name('operator.reviewer_eksternal.add');
    Route::get('/cari_prodi','Operator\ReviewerEksternalController@cariProdi')->name('operator.reviewer_eksternal.cari_prodi');
    Route::post('/','Operator\ReviewerEksternalController@post')->name('operator.reviewer_eksternal.post');
    Route::get('/{id}/edit','Operator\ReviewerEksternalController@edit')->name('operator.reviewer_eksternal.edit');
    Route::patch('/','Operator\ReviewerEksternalController@update')->name('operator.reviewer_eksternal.update');
    Route::delete('/','Operator\ReviewerEksternalController@delete')->name('operator.reviewer_eksternal.delete');
    Route::patch('/update_password','Operator\ReviewerEksternalController@updatePassword')->name('operator.reviewer_eksternal.update_password');
    Route::patch('/nonaktifkan_status/{id}','Operator\ReviewerEksternalController@nonAktifkanStatus')->name('operator.reviewer_eksternal.nonaktifkan_status');
    Route::patch('/aktifkan_status/{id}','Operator\ReviewerEksternalController@aktifkanStatus')->name('operator.reviewer_eksternal.aktifkan_status');
});

Route::group(['prefix'  => 'operator/manajemen_jadwal_input_usulan'],function(){
    Route::get('/','Operator\JadwalUsulanController@index')->name('operator.jadwal_usulan');
    Route::post('/','Operator\JadwalUsulanController@post')->name('operator.jadwal_usulan.add');
    Route::delete('/','Operator\JadwalUsulanController@delete')->name('operator.jadwal_usulan.delete');
    Route::patch('/aktifkan_status/{id}','Operator\JadwalUsulanController@aktifkanStatus')->name('operator.jadwal_usulan.aktifkan_status');
    Route::patch('/nonaktifkan_status/{id}','Operator\JadwalUsulanController@nonAktifkanStatus')->name('operator.jadwal_usulan.nonaktifkan_status');
});

Route::group(['prefix'  => 'operator/manajemen_jadwal_review_usulan'],function(){
    Route::get('/','Operator\JadwalReviewUsulanController@index')->name('operator.jadwal_review_usulan');
    Route::post('/','Operator\JadwalReviewUsulanController@post')->name('operator.jadwal_review_usulan.add');
    Route::delete('/','Operator\JadwalReviewUsulanController@delete')->name('operator.jadwal_review_usulan.delete');
    Route::patch('/aktifkan_status/{id}','Operator\JadwalReviewUsulanController@aktifkanStatus')->name('operator.jadwal_review_usulan.aktifkan_status');
    Route::patch('/nonaktifkan_status/{id}','Operator\JadwalReviewUsulanController@nonAktifkanStatus')->name('operator.jadwal_review_usulan.nonaktifkan_status');
});

// Route Pengusul
Route::group(['prefix'  => 'pengusul/'],function(){
    Route::get('/','Pengusul\DashboardController@index')->name('pengusul.dashboard');
});

Route::group(['prefix'  => 'pengusul/manajemen_usulan'],function(){
    Route::get('/','Pengusul\UsulanController@index')->name('pengusul.usulan');
    Route::get('/{slug}/tambah_usulan_baru','Pengusul\UsulanController@create')->name('pengusul.usulan.create');
    Route::post('/','Pengusul\UsulanController@post')->name('pengusul.usulan.add');
    Route::get('/{slug}/edit_usulan/{id}','Pengusul\UsulanController@edit')->name('pengusul.usulan.edit');
    Route::patch('/{id}','Pengusul\UsulanController@update')->name('pengusul.usulan.update');
    Route::delete('/','Pengusul\UsulanController@delete')->name('pengusul.usulan.delete');
    Route::post('/anggota','Pengusul\UsulanController@anggotaPost')->name('pengusul.usulan.anggota_post');
    Route::post('/anggota_eksternal','Pengusul\UsulanController@anggotaEksternalPost')->name('pengusul.usulan.anggota_eksternal_post');
    Route::post('/anggota_mahasiswa','Pengusul\UsulanController@anggotaMahasiswaPost')->name('pengusul.usulan.anggota_mahasiswa_post');
    Route::post('/anggota_alumni','Pengusul\UsulanController@anggotaAlumniPost')->name('pengusul.usulan.anggota_alumni_post');
    Route::get('/{id}/get_anggota','Pengusul\UsulanController@getAnggota')->name('pengusul.usulan.get_anggota');
    Route::get('/cari_prodi','Pengusul\UsulanController@cariProdi')->name('admin.pembahasan.cari_prodi');
    // Route::get('/cari_anggota','Pengusul\UsulanController@cariAnggota')->name('deta.pembahasan.cari_anggota');
    Route::patch('/usulkan/{id}','Pengusul\UsulanController@usulkan')->name('pengusul.usulan.usulkan');
    Route::get('/detail_usulan/{id}/{slug}/{judul}','Pengusul\UsulanController@detailUsulan')->name('pengusul.usulan.detail');
    Route::post('/anggaran_honor','Pengusul\UsulanController@anggaranHonorPost')->name('pengusul.usulan.anggaran_honor_post');
    Route::post('/anggaran_habis','Pengusul\UsulanController@anggaranHabisPost')->name('pengusul.usulan.anggaran_habis_post');
    Route::post('/anggaran_penunjang','Pengusul\UsulanController@anggaranPenunjangPost')->name('pengusul.usulan.anggaran_penunjang_post');
    Route::post('/anggaran_lainnya','Pengusul\UsulanController@anggaranLainnyaPost')->name('pengusul.usulan.anggaran_lainnya_post');
    Route::get('/anggaran/{id}/cetak','Pengusul\UsulanController@anggaranCetak')->name('pengusul.usulan.anggaran.cetak');
    Route::get('/','Pengusul\UsulanController@index')->name('pengusul.usulan');

    Route::get('/{id}/detail_anggaran','Pengusul\UsulanController@detailAnggaran')->name('pengusul.usulan.detail_anggaran');

    Route::delete('/detail_anggaran','Pengusul\UsulanController@hapusHonor')->name('pengusul.usulan.detail_anggaran.honor_hapus');
    Route::delete('/detail_anggaran_habis','Pengusul\UsulanController@hapusHabis')->name('pengusul.usulan.detail_anggaran.habis_hapus');
    Route::delete('/detail_anggaran_penunjang','Pengusul\UsulanController@hapusPenunjang')->name('pengusul.usulan.detail_anggaran.penunjang_hapus');
    Route::delete('/detail_anggaran_lainnya','Pengusul\UsulanController@hapusLainnya')->name('pengusul.usulan.detail_anggaran.lainnya_hapus');

    Route::get('/{id}/get_anggaran','Pengusul\UsulanController@getAnggaran')->name('pengusul.usulan.get_anggaran');
    Route::get('/{id}/detail_judul','Pengusul\UsulanController@detailJudul')->name('pengusul.usulan.detail_judul');

    Route::get('/{id}/detail_anggota','Pengusul\UsulanController@detailAnggota')->name('pengusul.usulan.detail_anggota');
    Route::get('/cari_anggota','Pengusul\UsulanController@cariAnggota')->name('pengusul.usulan.cari_anggota');
    Route::get('/cari_mahasiswa','Pengusul\UsulanController@cariMahasiswa')->name('pengusul.usulan.cari_mahasiswa');
    Route::delete('/detail_anggota','Pengusul\UsulanController@hapusAnggota')->name('pengusul.usulan.detail_anggota.hapus');
    Route::delete('/detail_anggota_alumni','Pengusul\UsulanController@hapusAnggotaAlumni')->name('pengusul.usulan.detail_anggota.hapus_alumni');
    Route::delete('/detail_anggota_mahasiswa','Pengusul\UsulanController@hapusAnggotaMahasiswa')->name('pengusul.usulan.detail_anggota.hapus_mahasiswa');
    Route::delete('/detail_anggota_eksternal','Pengusul\UsulanController@hapusAnggotaEksternal')->name('pengusul.usulan.detail_anggota.hapus_eksternal');
    Route::get('/cari_skim','Pengusul\UsulanController@cariSkim')->name('deta.pembahasan.cari_skim');
    Route::get('/cari_unit','Pengusul\UsulanController@cariUnit')->name('deta.pembahasan.cari_unit');
});

Route::group(['prefix'  => 'pengusul/upload_laporan_kemajuan'],function(){
    Route::get('/','Pengusul\LaporanKemajuanController@index')->name('pengusul.laporan_kemajuan');
    Route::post('/','Pengusul\LaporanKemajuanController@uploadLaporan')->name('pengusul.upload_laporan_kemajuan');
    Route::patch('/','Pengusul\LaporanKemajuanController@uploadLaporanPerbaikan')->name('pengusul.upload_laporan_perbaikan');
    Route::get('/{id}/detail_judul','Pengusul\LaporanKemajuanController@detailJudul')->name('pengusul.upload_laporan_kemajuan.detail_judul');
    Route::get('/{id}/ubah_biaya','Pengusul\LaporanKemajuanController@ubahBiaya')->name('pengusul.upload_laporan_kemajuan.ubah_biaya');
    Route::patch('/ubah_biaya','Pengusul\LaporanKemajuanController@ubahBiayaPost')->name('pengusul.upload_laporan_kemajuan.ubah_biaya_post');
    Route::get('/detail_penilaian/{id}/{skim_id}/{slug}','Pengusul\LaporanKemajuanController@detailPenilaian')->name('pengusul.verifikasi.detail_penilaian');

    Route::get('/{id}/detail_anggaran','Pengusul\UsulanController@detailAnggaran')->name('pengusul.laporan_kemajuan.detail_anggaran');

    Route::delete('/detail_anggaran','Pengusul\UsulanController@hapusHonor')->name('pengusul.laporan_kemajuan.detail_anggaran.honor_hapus');
    Route::delete('/detail_anggaran_habis','Pengusul\UsulanController@hapusHabis')->name('pengusul.laporan_kemajuan.detail_anggaran.habis_hapus');
    Route::delete('/detail_anggaran_penunjang','Pengusul\UsulanController@hapusPenunjang')->name('pengusul.laporan_kemajuan.detail_anggaran.penunjang_hapus');
    Route::delete('/detail_anggaran_lainnya','Pengusul\UsulanController@hapusLainnya')->name('pengusul.laporan_kemajuan.detail_anggaran.lainnya_hapus');
});

Route::group(['prefix'  => 'pengusul/anggota_kegiatan'],function(){
    Route::get('/','Pengusul\AnggotaController@index')->name('pengusul.anggota');
});

Route::group(['prefix'  => 'pengusul/upload_laporan_akhir'],function(){
    Route::get('/','Pengusul\LaporanAkhirController@index')->name('pengusul.laporan_akhir');
    Route::post('/','Pengusul\LaporanAkhirController@uploadLaporan')->name('pengusul.upload_laporan_akhir');
    Route::get('/{id}/detail_judul','Pengusul\LaporanAkhirController@detailJudul')->name('pengusul.upload_laporan_akhir.detail_judul');
    Route::patch('/konfirmasi','Pengusul\LaporanAkhirController@konfirmasi')->name('pengusul.laporan_akhir.konfirmasi');
    Route::get('/{id}/luaran','Pengusul\LaporanAkhirController@luaran')->name('pengusul.laporan_akhir.luaran');
    Route::post('/{id}/luaran','Pengusul\LaporanAkhirController@luaranPost')->name('pengusul.laporan_akhir.luaran_post');
    Route::get('/cari_publikasi','Pengusul\LaporanAkhirController@cariPublikasi')->name('pengusul.usulan.cari_publikasi');
    Route::get('/usulan/{id}/detail_judul','Pengusul\LaporanAkhirController@detailJudul')->name('pengusul.usulan.detail_judul');
    Route::get('/luaran/{id}/edit','Pengusul\LaporanAkhirController@editLuaran')->name('pengusul.usulan.edit_luaran');
    Route::patch('/luaran','Pengusul\LaporanAkhirController@updateLuaran')->name('pengusul.usulan.update_luaran');
    Route::delete('/luaran','Pengusul\LaporanAkhirController@hapusLuaran')->name('pengusul.usulan.hapus_laran');
});


Route::group(['prefix'  => 'reviewer/'],function(){
    Route::get('/login','UserLoginController@showReviewerLoginForm')->name('panda.reviewer_login.form');
    Route::post('/pandaloginreviewer','UserLoginController@pandaLoginReviewer')->name('panda.login.reviewer');
    Route::get('/user_logout','UserLoginController@reviewerLogout')->name('logout_reviewer');
    Route::get('/','Reviewer\DashboardController@index')->name('reviewer.dashboard');
});

Route::group(['prefix'  => 'reviewer/usulan_dosen'],function(){
    Route::get('/','Reviewer\UsulanMenungguController@index')->name('reviewer.menunggu');
    Route::get('/{id}/detail/{slug}','Reviewer\UsulanMenungguController@detail')->name('reviewer.menunggu.detail');
    Route::get('/anggaran/{id}/cetak','Reviewer\UsulanMenungguController@anggaranCetak')->name('reviewer.usulan.anggaran.cetak');
    Route::get('/review/{id}/{skim_id}/{kegiatan}/{slug}','Reviewer\UsulanMenungguController@review')->name('reviewer.usulan.review');
    Route::post('/review','Reviewer\UsulanMenungguController@reviewPost')->name('reviewer.usulan.review_post');
});


Route::group(['prefix'  => 'reviewer/usulan_dosen/laporan_kemajuan'],function(){
    Route::get('/','Reviewer\LaporanKemajuanController@index')->name('reviewer.laporan_kemajuan');
    Route::get('/{id}/detail','Reviewer\LaporanKemajuanController@detail')->name('reviewer.laporan_kemajuan.detail');
    Route::get('/{id}/{skim_id}/review','Reviewer\LaporanKemajuanController@review')->name('reviewer.laporan_kemajuan.review');
    Route::post('/review','Reviewer\LaporanKemajuanController@reviewPost')->name('reviewer.laporan_kemajuan.review_post');
});

Route::group(['prefix'  => 'reviewer/riwayat_review_usulan'],function(){
    Route::get('/','Reviewer\RiwayatReviewController@index')->name('reviewer.riwayat');
    Route::get('/{id}/detail','Reviewer\RiwayatReviewController@detail')->name('operator.riwayat.detail');
});

Route::group(['prefix'  => 'reviewer/riwayat_review_laporan_kemajuan'],function(){
    Route::get('/','Reviewer\RiwayatReviewKemajuanController@index')->name('reviewer.riwayat_kemajuan');
    Route::get('/{id}/detail','Reviewer\RiwayatReviewKemajuanController@detail')->name('operator.riwayat_kemajuan.detail');
});
