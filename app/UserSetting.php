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

  protected static $valid_names = [
    'currency',
    'language'
  ];

  protected static $valid_values = [
    'currency' => [
      'EUR', 'USD'
    ],
    'language' => [
      'en', 'it'
    ]
  ];

  protected static $defaults = [
    'currency' => 'USD',
    'language' => 'en'
  ];

  public static function getHtmlFor( $user, $name ){
    $html = '<select class="form-control setting" id="'.$name.'" name="'.$name.'">';

    foreach( self::$valid_values[$name] as $value ){
      $selected = $user->getSetting($name) == $value ? ' selected' : '';
      $html .= '<option value="'.$value.'"'.$selected.'>'.$value.'</option>';
    }

    $html .= '</select>';

    return $html;
  }

  public static function getValidNames() {
    return self::$valid_names;
  }

  public static function getDefaults(){
    return self::$defaults;
  }

  public static function getDefault( $name ){
    if( isset(self::$defaults[$name]) ){
      return self::$defaults[$name];
    }
    return NULL;
  }

  public static function isValid( $name, $value ){
    return isset(self::$valid_values[$name]) &&
           in_array( $value, self::$valid_values[$name]);
  }

  public function user(){
    return $this->belongsTo('App\User');
  }
}
