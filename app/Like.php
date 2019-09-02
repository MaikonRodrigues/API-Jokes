<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id', 'piada_id', 'like',
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
