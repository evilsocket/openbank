<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Cache;

class UpdateBalance extends Command
{
    private function doPost($key){
      $data = array('addr' => $key);
      $opts = array('http' =>
          array(
              'method'  => 'POST',
              'header'  => "Content-Type: application/json\r\n" .
                           "Accept: application/json\r\n",
              'content' => @json_encode($data, true)
          )
      );

      $context  = @stream_context_create($opts);
      $result   = @file_get_contents('https://www.blockonomics.co/api/balance', false, $context);
      $response = @json_decode($result, true);

      return $response;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balanceupdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users balance from https://www.blockonomics.co/ API.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      Log::info('Balance update job started ...');

      foreach( \App\User::all() as $user ){
        $keys = $user->keys()->get();
        // Skip users with no keys
        if( !$keys )
          continue;

        Log::info( "Updating balance for '".$user->email."' ( id = ".$user->id." ) ..." );

        foreach( $keys as $key ){
          Log::info( "  Fetching balance for key '".$key->label."' ( ".$key->value." ) ..." );

          $response = $this->doPost( $key->value );

          if( !$response || !isset($response['response']) ){
            Log::error( "! Invalid response json." );
            continue;
          }

          $response = $response['response'];
          $total    = 0;
          foreach( $response as $address ){
            $total += $address['confirmed'];
          }
          $total /= 100000000.0;

          $key->balance = $total;
          $key->updated_at = time();
          $key->save();
        }
      }

      Log::info('Balance update job DONE.');
    }
}
