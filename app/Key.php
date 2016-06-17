<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
  protected $fillable = [
      'label', 'value', 'user_id'
  ];

  protected $hidden = [
      'id', 'user_id'
  ];

  const MIN_LABEL_LENGTH = 3;
  const MAX_LABEL_LENGTH = 255;
  const MIN_VALUE_LENGTH = 10;
  const MAX_VALUE_LENGTH = 255;

  public static function validate($key){
    if( !is_array($key) || !isset($key['label']) || !isset($key['value']) ){
      return FALSE;
    }
    else if( mb_strlen($key['label']) < self::MIN_LABEL_LENGTH || mb_strlen($key['label']) > self::MAX_LABEL_LENGTH ) {
      return FALSE;
    }
    else if( mb_strlen($key['value']) < self::MIN_VALUE_LENGTH || mb_strlen($key['value']) > self::MAX_VALUE_LENGTH ) {
      return FALSE;
    }

    return TRUE;
  }

  public function user(){
    return $this->belongsTo('App\User');
  }
}
