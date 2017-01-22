<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
  protected $fillable = [
      'name', 'value', 'user_id'
  ];

  protected $hidden = [
      'id', 'user_id'
  ];

  protected static $registered = NULL;

  public static function available() {
    if( self::$registered === NULL ){
      self::$registered = [
        'currency'              => new UserSettingDescriptor(
          'currency',
          'Currency',
          Price::currecies(),
          'USD'
        ),

        'chart_type'            => new UserSettingDescriptor(
          'chart_type',
          'Dashboard Chart Default Type',
          Price::chartTypes(),
          Price::CHART_TYPE_1H
        ),

        /*
        TODO rimuovere?
        'blockonomics_api_key' => new UserSettingDescriptor(
          'blockonomics_api_key',
          'Blockonomics.com API Key<br/><small style="color:#999; font-weight:normal">Needed for xPubs with more than 50 addresses.</small>',
          ''
        )
        */
      ];
    }

    return self::$registered;
  }

  public static function isValid( $name, $value ){
    $avail = self::available();
    if( isset($avail[$name]) ){
      return $avail[$name]->isValidValue($value);
    }

    return FALSE;
  }

  public function user(){
    return $this->belongsTo('App\User');
  }
}
