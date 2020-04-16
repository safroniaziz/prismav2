<?php

use Illuminate\Database\Seeder;

class JadwalReviewUsulansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jadwal = [
            ['tanggal_awal'  =>  '2020-04-06','tanggal_akhir' =>  '2020-04-17','status' =>  '1'],
        ];
        \App\JadwalReviewUsulan::insert($jadwal);
    }
}
