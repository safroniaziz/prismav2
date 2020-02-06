<?php

use Illuminate\Database\Seeder;

class SkimsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skim = [
            ['nm_skim'  =>  'Pembinaan','tahun' =>  '2018'],
            ['nm_skim'  =>  'Unggulan','tahun' =>  '2018'],
            ['nm_skim'  =>  'Iptek','tahun' =>  '2019'],
            ['nm_skim'  =>  'Riset','tahun' =>  '2020'],
            ['nm_skim'  =>  'Kolaborasi','tahun' =>  '2020'],
        ];
        \App\Skim::insert($skim);
    }
}
