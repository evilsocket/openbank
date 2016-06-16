<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('currencies')->insert([
        'name'   => 'EUR',
        'symbol' => '€',
        'html'   => '&euro;'
      ]);
      DB::table('currencies')->insert([
        'name'   => 'USD',
        'symbol' => '$',
        'html'   => '&dollar;'
      ]);
    }
}
