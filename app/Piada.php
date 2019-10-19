<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Piada extends Model{
        
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function likes()
    {
        return $this->hasMany('App\Like');
    }
    public function reacao()
    {
        return $this->hasMany('App\Reacao');
    }
    
  /*  public function categoria()
    {
        return $this->hasOne(Categoria::class);
    }*/
}
