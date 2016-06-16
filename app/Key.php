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

  public function user(){
    return $this->belongsTo('App\User');
  }
}
