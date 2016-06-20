<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSettingDescriptor {
  protected $name;
  protected $label;
  protected $values = array();
  protected $default = NULL;

  public function __construct( $name, $label, $values, $default = NULL ) {
    $this->name    = $name;
    $this->label   = $label;
    $this->values  = $values;
    $this->default = $default;
  }

  private function hasLabels() {
    $labels = array_keys($this->values);
    return $labels && $labels[0] !== 0;
  }

  public function label() {
    return $this->label;
  }

  public function html( $user ) {
    $current = $user->getSetting($this->name);

    if( is_array($this->values) ){
      $html = '<select class="form-control setting" id="'.$this->name.'" name="'.$this->name.'">';

      foreach( $this->values as $key => $value ){
        $selected = $current == $value ? ' selected' : '';
        $label    = $this->hasLabels() ? $key : $value;
        $html .= '<option value="'.$value.'"'.$selected.'>'.$label.'</option>';
      }

      $html .= '</select>';
    }
    else {
      $html = '<input type="text" class="form-control setting" id="'.$this->name.'" name="'.$this->name.'" value="'.$current.'">';
    }

    return $html;
  }

  public function def() {
    return $this->default;
  }

  public function isValidValue( $value ) {
    if( is_array($this->values) ){
      return in_array( $value, $this->values );
    }
    else if( $this->values !== '' ){
      return $value == $this->values;
    }

    return true;
  }
}

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

        'blockonomics_api_key' => new UserSettingDescriptor(
          'blockonomics_api_key',
          'Blockonomics.com API Key<br/><small style="color:#999; font-weight:normal">Needed for xPubs with more than 50 addresses.</small>',
          ''
        )
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
