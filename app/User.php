<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Cache;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password', 'remember_token', 'api_token'
    ];

    public function purgeCache() {
      $cache_key = 'UserController@getUserProfile('.$this->api_token.')';
      Cache::forget($cache_key);
    }

    public function createDefaultSettings() {
      foreach( UserSetting::getDefaults() as $name => $value ){
        $this->setSetting( $name, $value );
      }
    }

    public function getSetting( $name, $ret_default = true ){
      $record = UserSetting::where( 'user_id', '=', $this->id )->where( 'name', '=', $name )->first();
      if( $record !== NULL ){
        return $record->value;
      }

      return $ret_default ? UserSetting::getDefault($name) : NULL;
    }

    public function setSetting( $name, $value ) {
      if( UserSetting::isValid( $name, $value ) ){
        $record = UserSetting::where( 'user_id', '=', $this->id )->where( 'name', '=', $name )->first();
        // Create new
        if( $record === NULL ){
          UserSetting::create([
            'user_id' => $this->id,
            'name' => $name,
            'value' => $value
          ]);
        }
        // Update
        else {
          $record->value = $value;
          $record->save();
        }
      }

      $this->purgeCache();
    }

    public function addKey( $label, $value ) {
      $record = Key::where( 'user_id', '=', $this->id )->where( 'value', '=', $value )->first();
      // Create new
      if( $record === NULL ){
        Key::create([
          'user_id' => $this->id,
          'label' => $label,
          'value' => $value
        ]);
      }
      // Update
      else {
        $record->label = $label;
        $record->save();
      }

      $this->purgeCache();
    }

    public function delKey( $value ){
      $record = Key::where( 'user_id', '=', $this->id )->where( 'value', '=', $value )->first();
      if( $record !== NULL ){
        $record->destroy($record->id);
        $this->purgeCache();
        return TRUE;
      }
      return FALSE;
    }

    public function updateKey( $value, $label ){
      $record = Key::where( 'user_id', '=', $this->id )->where( 'value', '=', $value )->first();
      if( $record !== NULL ){
        $record->label = $label;
        $record->save();
        $this->purgeCache();
        return TRUE;
      }
      return FALSE;
    }

    public function settings(){
      return $this->hasMany(UserSetting::class);
    }

    public function keys(){
      return $this->hasMany(Key::class);
    }
}
