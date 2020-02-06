<?php

use Illuminate\Database\Seeder;

class BidangPenelitiansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bidangs = [
            ['nm_bidang'  =>  'Keanekaragaman Hayati, Lingkungan dan Maritim'],
            ['nm_bidang'  =>  'Ketahanan Pangan, Pertanian dan Peternakan'],
            ['nm_bidang'  =>  'Kesehatan dan Farmasi'],
            ['nm_bidang'  =>  'Material Maju'],
            ['nm_bidang'  =>  'Energi dan Transportasi'],
            ['nm_bidang'  =>  'Teknologi, Informasi dan Komunikasi serta Pertahanan dan Keamanan'],
            ['nm_bidang'  =>  'Dinamika Sosial, Kemanusiaan dan Kebudayaan'],
            ['nm_bidang'  =>  'Inovasi Teknologi dan Pendayagunaan Iptek'],
        ];
        \App\BidangPenelitian::insert($bidangs);
    }
}
