<?php
namespace App;

/*
 * This class handles registration, HTML generation and validation of the available
 * user settings ( user_setting table ), to see how this works take a look at the
 * "::available()" method \App::UserSetting application model class.
 */
class UserSettingDescriptor
{
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
