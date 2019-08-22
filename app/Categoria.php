<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public function piada(){
        return $this->belongsTo(Piada::class);
    }
}
