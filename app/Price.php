<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Price extends Model
{
  protected $fillable = [
    'currency', 'price', 'markets'
  ];

  public static function current( $currency = 'USD' ){
    $key = "Price::current($currency)";
    if( !Cache::has($key) ){
      $v = Price::where('currency', '=', $currency)->orderBy('updated_at', 'DESC')->first();
      Cache::put( $key, $v, 1 );
    }

    return Cache::get($key);
  }

  private static function getDate($modifier) {
    $date = new \DateTime;
    $date->modify($modifier);
    return $date->format('Y-m-d H:i:s');
  }

  public static function trends( $price ){
    $key = "Price::trends('.$price->currency.')";
    if( !Cache::has($key) ){
      $trends = [
        '24h' => self::getDate('-24 hours'),
        '1w'  => self::getDate('-7 days'),
        '1m'  => self::getDate('-1 month')
      ];

      $results = [];

      foreach( $trends as $label => $date ) {
        $prev = Price::where('currency', '=', $price->currency)
                  ->where('updated_at', '<=', $date )
                  ->orderBy('updated_at', 'DESC')->first();

        $results[$label] = $prev === NULL ? 0.0 : ( ( $price->price - $prev->price ) / $prev->price ) * 100.0;
      }

      Cache::put( $key, $results, 1 );
    }

    return Cache::get($key);
  }

  public static function history( $currency = 'EUR', $chart_type = 0 ){
    $key = "Price::history($currency,$chart_type)";
    if( !Cache::has($key) ){

      $configs = array(
        1 => array( 'mod' => 30,   'limit' => 48 ), // 1 every half hour ( 30 minutes ), limit to 48 ( 24 hours )
        2 => array( 'mod' => 1440, 'limit' => 7 ),  // 1 every day  ( 1440 minutes ), limit 7
        3 => array( 'mod' => 1440, 'limit' => 30 )  // 1 every day, limit 30
      );

      $query = "SELECT * FROM (" .
        " SELECT prices.*, @row := @row + 1 AS rownum ".
          " FROM (".
             " SELECT @row :=0" .
          " ) r, prices WHERE currency = 'EUR'" .
      " ) ranked ".
      " WHERE rownum %% %d = 0 ORDER BY id DESC LIMIT %d";

      switch( $chart_type ){
        // 1 hour
        case 0:
          $v = Price::where('currency', '=', $currency)
                        ->orderBy('id', 'DESC')
                        ->limit(60)
                        ->get();
        break;

        // 24 hours
        case 1:
        // 1 week
        case 2:
        // 1 month
        case 3:

          $cfg   = $configs[$chart_type];
          $query = sprintf( $query, $cfg['mod'], $cfg['limit'] );
          $v     = Price::hydrateRaw( \DB::raw($query) );

        break;
      }

      Cache::put( $key, $v, 1 );
    }

    return Cache::get($key);
  }

  public static function rates( $currency = 'EUR' ){
    $key = "Price::rates($currency)";
    if( !Cache::has($key) ){
      $rates = array(
        'data' => array(
          array(),
          array()
        ),
        'labels' => array(),
        'series' => array('Bid', 'Ask')
      );

      $current = Price::where('currency', '=', $currency)
                        ->orderBy('id', 'DESC')
                        ->first();

      $markets = json_decode( $current->markets, true );

      foreach( $markets as $name => $m ) {
        $rates['labels'][] = $m['display_name'];
        $rates['data'][0][] = $m['rates']['bid'];
        $rates['data'][1][] = $m['rates']['ask'];
      }

      Cache::put( $key, $rates, 1 );
    }

    return Cache::get($key);
  }
}
