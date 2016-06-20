<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Log;
use Cache;

class Blockonomics
{
  public static function getKeyBalance( $key, $api_key = NULL ){
    $data = array('addr' => $key);
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\n" .
                         "Accept: application/json\r\n" .
                         ( $api_key ? "Authorization: Bearer $api_key\r\n" : '' ),
            'content' => @json_encode($data, true)
        )
    );

    $context  = @stream_context_create($opts);
    $result   = @file_get_contents('https://www.blockonomics.co/api/balance', false, $context);
    $response = @json_decode($result, true);
    $balance  = 0.0;

    if( !$response || !isset($response['response']) ){
      return NULL;
    }

    $response = $response['response'];
    foreach( $response as $address ){
      $balance += $address['confirmed'];
    }

    return $balance / 100000000.0;
  }
};

class UpdateBalance extends Command
{
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
      Log::info('[BALANCE JOB] Started ...');

      foreach( \App\User::all() as $user ){
        $keys = $user->keys()->get();
        // Skip users with no keys
        if( !$keys )
          continue;

        $api_key = $user->getSetting('blockonomics_api_key');

        foreach( $keys as $key ){
          $balance = Blockonomics::getKeyBalance( $key->value, $api_key );
          if( $balance === NULL ){
            Log::error( "[BALANCE JOB] Invalid response json." );
            continue;
          }

          $key->balance = $balance;
          $key->save();
        }

        $user->purgeCache();
      }

      Log::info('[BALANCE JOB] Done.');
    }
}
