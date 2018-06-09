<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
  protected $table='detallefactura';
  public function producto(){
    return $this->belongsTo('App\Producto','productos_id');
  }
  public function cliente(){
    return $this->belongsTo('App\Factura','facturas_id');
  }
}
