<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class UpdatePrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'priceupdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the price from blockchain API.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      Log::info( "[PRICE JOB] started.." );
      //$data = @file_get_contents('https://api.bitcoinaverage.com/all');
      $data = @file_get_contents('https://blockchain.info/ticker');
      $json = @json_decode( $data, true );

      if( !$data || !$json ){
        Log::error( '[PRICE JOB] Error while contacting blockchain API' );
        die;
      }

      $currencies = array_keys($json);
      foreach( $currencies as $curr ){
        if( $curr != '' && $curr != 'ignored_exchanges' && $curr != 'timestamp' ){
          $price   = $json[$curr]['last'];
          $markets = 'BlockChain'; //json_encode( $json[$curr]['exchanges'], true );

          \App\Price::create(array(
            'currency'  => $curr,
            'price'     => $price,
            'markets'   => json_encode([
		'blockchain' => [ 'display_name' => 'blockchain', 'rates' => [ 'bid' => $json[$curr]['buy'], 'ask' => $json[$curr]['sell'] ] ]
            ])
          ));
        }
      }

      $date = new \DateTime;
      $date->modify('-2 months');
      $formatted_date = $date->format('Y-m-d H:i:s');

      $deleted = \App\Price::where('created_at','<',$formatted_date)->delete();
      if( $deleted > 0 )
        Log::info( "[PRICE JOB] Deleted $deleted old price records." );
    }
}
