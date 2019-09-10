<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesLike extends Model
{
    protected $fillable = [
        'user_id', 'piada_id', 'deslike',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function piada()
    {
        return $this->belongsTo('App\Piada');
    }
}
