<?php

use Illuminate\Database\Seeder;

class FormulirsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formulir = factory(App\Formulir::class, 10)->create();
    }
}
