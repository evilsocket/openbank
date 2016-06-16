<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
  protected $fillable = [
    'currency', 'price'
  ];

  public static function current( $currency = 'USD' ){
    return Price::where('currency', '=', $currency)->orderBy('updated_at', 'DESC')->first();
  }

  private static function getDate($modifier) {
    $date = new \DateTime;
    $date->modify($modifier);
    return $date->format('Y-m-d H:i:s');
  }

  public static function trends( $price ){
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

    return $results;
  }

  public static function history( $currency = 'EUR', $limit = 100 ){
    return Price::where('currency', '=', $currency)
                  ->orderBy('id', 'DESC')
                  ->limit($limit)
                  ->get();
  }
}
