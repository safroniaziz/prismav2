<?php

use Illuminate\Database\Seeder;

class UsulansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usulan = [
            [
                'judul_penelitian'  =>  'Analisis Dan Pemetaan Daerah Banjir Dengan Metode Fuzzy Simple Adaptive Weighting Berbasis Spasial Guna Memetakan Tingkat Daerah Rawan Banjir (Studi Kasus: Daerah Aliran Sungai Bengkulu)',
                'skim_id' =>  1,
                'bidang_id' =>  1,
                'ketua_peneliti_nip' =>  "197308142006042001",
                'ketua_peneliti_nama' =>  "Ernawati",
                'abstrak' =>  "Kota Bengkulu yang terletak di pesisir barat Pulau Sumatra memiliki garis pantai sepanjang 7 km dengan berbagai khasanah keindahan pantai yang dimiliki. Pantai di Kota Bengkulu merupakan muara dua aliran sungai, yaitu; Sungai Bengkulu dan Sungai Jenggalu. Sungai-sungai tersebut sering terjadi luapan air hujan dari hulu sungai, yang berasal dari 5 kabupaten lainnya. Aktivitas di hulu sungai yang sudah tidak terkendali akibat aktivitas pertambangan dan perkebunan, mengakibatkan banjir khususnya yang terjadi di area sekitar Daerah Aliran Sungai (DAS) Sungai Bengkulu. Banjir yang terjadi di DAS Sungai Bengkulu tidak hanya merugikan materi, tetapi telah memakan korban nyawa akibat dari banjir tersebut.
                                Usulan pada penelitian ini yaitu; (1) Analisis daerah banjir dengan metode Fuzzy Simple Adaptive Weighting, yang diharapkan dapat menentukan tingkat daerah rawan banjir, khususnya daerah yang berdampak pada pemukiman masyarakat dan fasilitas umum seperti jalan dan jembatan di sepanjang DAS Sungai Bengkulu; (2) Memetakan daerah tingkat rawan banjir berbasis spasial, untuk memudahkan penetuan daerah tingkat rawan banjir. Adapun usulan penelitian ini merupakan bagian dari pengembangan Rencana Induk Penelitian (RIP) Universitas Bengkulu (UNIB) tentang ekologis wilayah pesisir untuk turut aktiv dalam pembangunan wilayah, termasuk mencegah kerusakan lingkungan.
                                Penelitian yang diusulkan memiliki tujuan jangka pendek yaitu; (1) mengetahui tingkat akurasi metode Fuzzy Simple Adaptive Weighting dalam melakukan analisis tingkat daerah rawan banjir, (2) Memetakan tingkat daerah rawan banjir dalam bentuk spasial untuk wilayah DAS Sungai Bengkulu. Adapun tujuan jangka panjang yang ingin dicapai, yaitu; (1) dapat menjadi sumber acuan kebijakan Pemerintah Daerah dalam penataan ruangan khususnya di sepanjang DAS Sungai Bengkulu, dan (2) dapat menjadi sumber acuan kebijakan dalam melakukan rehabilitasi DAS Sungai Bengkulu dari kerusakan lingkungan.
                                Penelitian berfokus pada; (1) perancangan Sistem Informasi Geografis (SIG) tingkat daerah rawan banjir dengan mengimplementasikan metode Fuzzy Simple Adaptive Weighting, (2) Pengumpulan data komponen variabel tingkat rawan banjir, (3) pemetaan tingkat daerah rawan banjir di Daerah Aliran Sungai Bengkulu, dan (4) pengumpulan data topograsi daerah yang terkena banjir luapan Sungai Bengkulu.
                                Keluaran dari penelitian yang diusulkan berupa; prosiding pada Seminar Internasional atau publikasi pada jurnal nasional.",
                'kata_kunci'    =>  'banjir,Daerah Aliran Sungai (DAS),pemetaan,Fuzzy Simple Adaptive Weighting',
                'peta_jalan' =>  null,
                'biaya_diusulkan'   =>  '10000000',
                'tahun_usulan'   =>  '2020',
            ],

            [
                'judul_penelitian'  =>  'Sistem Informasi Tesis Maksi FEB Universitas Bengkulu adalah aplikasi yang digunakan untuk melakukan kegiatan bimbingan mahasiswa kepada dosen, ',
                'skim_id' =>  1,
                'bidang_id' =>  1,
                'ketua_peneliti_nip' =>  "198909032015041004",
                'ketua_peneliti_nama' =>  "Yudi Setiawan",
                'abstrak' =>  "Sistem Informasi ini mencakup segala hal yang berhubungan dengan tesis mahasiswa, dimulai dari proses bimbingan sebelum melakukan seminar
                                proposal, melakukan bimbingan saat penelitian, melakukan bimbingan revisi, bahkan sampai sidang tesis.
                                Dengan menggunakan aplikasi ini juga mahasiswa dapat mencetak berkas-berkas yang dibutuhkan tanpa harus mendatangi
                                sekretaris program study, sehingga dapat lebih menghemat waktu. Aplikasi ini juga terhubung langsung dengan pangkalan
                                data universitas bengkulu, sehingga saat ingin menggunakan aplikasi mahasiswa, dosen dapat login menggunakan password portal akademik,
                                dengan demikian dapat mengurangi kemungkinan penyalahgunaan akun.",
                'kata_kunci'    =>  'tesis, unib',
                'peta_jalan' =>  null,
                'biaya_diusulkan'   =>  '20000000',
                'tahun_usulan'   =>  '2020',
            ],
        ];
        \App\Usulan::insert($usulan);
    }
}
