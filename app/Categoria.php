<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Piada;

class Categoria extends Model
{
  protected $fillable = [
    'id', 'nome', 'estado',
];
}
