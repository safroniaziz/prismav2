<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(SkimsTableSeeder::class);
        $this->call(BidangPenelitiansTableSeeder::class);
        $this->call(UsulansTableSeeder::class);
        $this->call(FormulirsTableSeeder::class);
    }
}
