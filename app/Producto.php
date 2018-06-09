<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    protected $table='productos';

    public function proveedores(){
      return $this->belongsTo('App\Proveedor','proveedores_id');
    }
}
