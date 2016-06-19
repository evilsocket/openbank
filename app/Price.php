<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Price extends Model
{
  protected $fillable = [
    'currency', 'price', 'markets'
  ];

  const CHART_TYPE_1H  = 0;
  const CHART_TYPE_24H = 1;
  const CHART_TYPE_1W  = 2;
  const CHART_TYPE_1M  = 3;

  public static function isValidChartType($type) {
    return in_array( $type,
      array(
        self::CHART_TYPE_1H,
        self::CHART_TYPE_24H,
        self::CHART_TYPE_1W,
        self::CHART_TYPE_1M
      )
    );
  }

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
    $chart_type = self::isValidChartType( $chart_type ) ? (int)$chart_type : \App\Price::CHART_TYPE_1H;
    $key        = "Price::history($currency,$chart_type)";

    if( !Cache::has($key) ){

      $configs = array(
        self::CHART_TYPE_24H => array( 'mod' => 30,   'limit' => 48 ), // 1 every half hour ( 30 minutes ), limit to 48 ( 24 hours )
        self::CHART_TYPE_1W  => array( 'mod' => 1440, 'limit' => 7 ),  // 1 every day  ( 1440 minutes ), limit 7
        self::CHART_TYPE_1M  => array( 'mod' => 1440, 'limit' => 30 )  // 1 every day, limit 30
      );

      $limit = 60;
      $query = "SELECT * FROM (" .
        " SELECT prices.*, @row := @row + 1 AS rownum ".
          " FROM (".
             " SELECT @row :=0" .
          " ) r, prices WHERE currency = '".$currency."'" .
      " ) ranked ".
      " WHERE rownum %% %d = 0 ORDER BY id DESC LIMIT %d";

      switch( $chart_type ){
        case self::CHART_TYPE_1H:
          $v = Price::where('currency', '=', $currency)
                        ->orderBy('id', 'DESC')
                        ->limit($limit)
                        ->get();
        break;

        case self::CHART_TYPE_24H :
        case self::CHART_TYPE_1W  :
        case self::CHART_TYPE_1M  :

          $cfg   = $configs[$chart_type];
          $limit = $cfg['limit'];
          $query = sprintf( $query, $cfg['mod'], $cfg['limit'] );
          $v     = Price::hydrateRaw( \DB::raw($query) );

        break;
      }

      $npoints = count($v);
      $history = array();

      foreach( $v as $p ){
        $history[] = array(
          'price'    => $p->price,
          'ts'       => $p->created_at->timestamp,
          'complete' => $npoints == $limit
        );
      }

      Cache::put( $key, $history, 1 );
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
