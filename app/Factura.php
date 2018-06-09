<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
  protected $table = 'facturas';

  //Relaciones
  public function user(){
    return $this->belongsTo('App\User','user_id');
  }
  public function cliente(){
    return $this->belongsTo('App\Cliente','clientes_id');
  }
}
