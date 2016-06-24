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

  public static $chart_type_labels = [
    self::CHART_TYPE_1H => 'Last Hour',
    self::CHART_TYPE_24H => 'Last 24 Hours',
    self::CHART_TYPE_1W => 'Last Week',
    self::CHART_TYPE_1M => 'Last Month'
  ];

  public static function currecies() {
    return array( 'EUR', 'USD' );
  }

  public static function chartTypes() {
    return array(
      'Last Hour'     => self::CHART_TYPE_1H,
      'Last 24 Hours' => self::CHART_TYPE_24H,
      'Last Week'     => self::CHART_TYPE_1W,
      'Last Month'    => self::CHART_TYPE_1M
    );
  }

  public static function isValidChartType($type) {
    return in_array( $type, self::chartTypes() );
  }

  public static function current( $currency = 'USD' ){
    $key = "Price::current($currency)";
    if( !Cache::has($key) ){
      $v = Price::where('currency', '=', $currency)->orderBy('updated_at', 'DESC')->first();
      Cache::put( $key, $v, 1 );
    }

    return Cache::get($key);
  }

  private static function getAverage($currency, $cluster, $limit) {
    $key = "Price::getAverage($currency,$cluster,$limit)";
    if( !Cache::has($key) ){
      $average = 0.0;
      $rows = \DB::table('prices')
                ->select( \DB::raw( "DATE_FORMAT( created_at, '$cluster' ) AS cluster, ROUND( AVG(price), 2 ) as price" ) )
                ->where( 'currency', '=', $currency )
                ->groupBy( 'cluster' )
                ->orderBy( 'id', 'desc' )
                ->limit( $limit )
                ->get();

      foreach( $rows as $row ){
        $average += $row->price;
      }

      $average /= count($rows);

      Cache::put( $key, $average, 60 );
    }

    return Cache::get($key);
  }

  public static function trends( $price ){
    $key = "Price::trends('.$price->currency.').v2";

    if( !Cache::has($key) ){
      $avg24h = self::getAverage( $price->currency, '%d-%m-%Y %H:00', 24 );
      $avg1w  = self::getAverage( $price->currency, '%d-%m-%Y', 7 );
      $avg1m  = self::getAverage( $price->currency, '%d-%m-%Y', 30 );

      $results = array(
        '24h' => ( ( $price->price - $avg24h ) / $avg24h ) * 100.0,
        '1w'  => ( ( $price->price - $avg1w ) / $avg1w ) * 100.0,
        '1m'  =>( ( $price->price - $avg24h ) / $avg1m ) * 100.0,
      );

      Cache::put( $key, $results, 1 );
    }

    return Cache::get($key);
  }

  public static function history( $currency = 'EUR', $chart_type = 0 ){
    $chart_type = self::isValidChartType( $chart_type ) ? (int)$chart_type : \App\Price::CHART_TYPE_1H;
    $key        = "Price::history($currency,$chart_type)";

    if( !Cache::has($key) ){
      $configs = array(
        self::CHART_TYPE_24H => [ 'cluster' => '%d-%m-%Y %H:00', 'limit' => 24 ],
        self::CHART_TYPE_1W  => [ 'cluster' => '%d-%m-%Y',       'limit' => 7 ],
        self::CHART_TYPE_1M  => [ 'cluster' => '%d-%m-%Y',       'limit' => 30 ]
      );

      switch( $chart_type ){
        case self::CHART_TYPE_1H:

          $v = Price::where('currency', '=', $currency)
                        ->orderBy('id', 'DESC')
                        ->limit(60)
                        ->get();

          $history = array();
          foreach( $v as $p ){
            $history[] = array(
              'price'    => $p->price,
              'ts'       => $p->created_at->timestamp,
              'complete' => true
            );
          }

        break;

        case self::CHART_TYPE_24H :
        case self::CHART_TYPE_1W  :
        case self::CHART_TYPE_1M  :

          $cfg     = $configs[$chart_type];
          $cluster = $cfg['cluster'];
          $limit   = $cfg['limit'];

          $v = \DB::table('prices')
                ->select( \DB::raw( "DATE_FORMAT( created_at, '$cluster' ) AS cluster, ROUND( AVG(price), 2 ) as price" ) )
                ->where( 'currency', '=', $currency )
                ->groupBy( 'cluster' )
                ->orderBy( 'id', 'desc' )
                ->limit( $limit )
                ->get();

          $npoints = count($v);
          $history = array();

          foreach( $v as $p ){
            $history[] = array(
              'price'    => $p->price,
              'ts'       => strtotime( $p->cluster ),
              'complete' => $npoints == $limit
            );
          }

        break;
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
