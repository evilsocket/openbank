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

        if( $name == 'currency' ){
          Cache::forget( "user_".$this->id."#getCurrency" );
        }
      }
    }

    public function getCurrency() {
      $key = "user_".$this->id."#getCurrency";
      if( !Cache::has($key) ){
        $v = \App\Currency::where( 'name', '=', $this->getSetting('currency') )->first();
        Cache::put( $key, $v, 1440 );
      }

      return Cache::get($key);
    }

    public function getKeys() {
      $key = "user_".$this->id."#getKeys";
      if( !Cache::has($key) ){
        $v = $this->keys()->orderBy('balance', 'DESC')->orderBy('updated_at', 'DESC')->get();
        Cache::put( $key, $v, 1440 );
      }

      return Cache::get($key);
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

      Cache::forget( "user_".$this->id."#getKeys" );
    }

    public function delKey( $value ){
      $record = Key::where( 'user_id', '=', $this->id )->where( 'value', '=', $value )->first();
      if( $record !== NULL ){
        $record->destroy($record->id);
        Cache::forget( "user_".$this->id."#getKeys" );
        return TRUE;
      }
      return FALSE;
    }

    public function updateKey( $value, $label ){
      $record = Key::where( 'user_id', '=', $this->id )->where( 'value', '=', $value )->first();
      if( $record !== NULL ){
        $record->label = $label;
        $record->save();
        Cache::forget( "user_".$this->id."#getKeys" );
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
