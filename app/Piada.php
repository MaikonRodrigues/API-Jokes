<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Piada extends Model{
        
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function categoria()
    {
        return $this->hasOne(Categoria::class);
    }
}
