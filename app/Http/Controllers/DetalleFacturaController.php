<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\DetalleFactura;
use App\Factura;
use App\Producto;

class DetalleFacturaController extends Controller
{
  public function index(Request $request){
    $detallefacturas = DetalleFactura::all();
    return response()->json(array(
      'detallefacturas'=> $detallefacturas,
      'status'=>'success'
    ),200);
  }

  public function show($id){
    $detallefactura = DetalleFactura::where('facturas_id','=',$id)->get();
    return response()->json(array(
      'detallefactura'=>$detallefactura,
      'status'=>'success'),200);
  }

  public function store(Request $request){
    $hash = $request ->header('Authorization',null);
    $jwtAuth=new JwtAuth();
    $checkToken = $jwtAuth->checkToken($hash);
    if($checkToken){
      //recoger datos post
      $json=$request->input('json',null);
      $params = json_decode($json);
      $params_array = json_decode($json,true);
      //User identificado
      $user = $jwtAuth->checkToken($hash,true);
      //validacion
      $validate = Validator::make($params_array,[
        'cantidadCompra'=>'required'
      ]);
      if($validate->fails()){
        return response()->json($validate->errors(),400);
      }
      //guardar detallefacturas
      $detallefactura = new DetalleFactura();
      //$detallefactura->id = $params->id;
      $product = Producto::find($params->productos_id);
      if(is_null($product)){
        $data= array(
          'message'=>'No existe el producto',
          'status'=> 'error',
          'code'=>300,
        );
        return response()->json($data,200);
      }
      $factura = Factura::find($params->facturas_id);
      if(is_null($factura)){
        $data= array(
          'message'=>'No existe el producto',
          'status'=> 'error',
          'code'=>300,
        );
        return response()->json($data,200);
      }
      $detallefactura->productos_id = $params->productos_id;
      $detallefactura->facturas_id = $params->facturas_id;
      $detallefactura->cantidadCompra = $params->cantidadCompra;
      $detallefactura->save();
      $data= array(
        'detallefactura'=>$detallefactura,
        'status'=> 'success',
        'code'=>200,
      );
    }else{
      //devolver errors
      $data= array(
        'message'=>'login malaso',
        'status'=> 'error',
        'code'=>300,
      );
    }
    return response()->json($data,200);
  }

  public function update($id,Request $request){
    $hash = $request ->header('Authorization',null);
    $jwtAuth = new JwtAuth();
    $checkToken = $jwtAuth->checkToken($hash);
    if($checkToken){
      //Recoger parametros post
      $json = $request->input('json',null);
      $params = json_decode($json);
      $params_array = json_decode($json,true);
      //Validar datos
      $validate = Validator::make($params_array,[
        'clientes_id'=>'required',
        'total'=>'required',
        'estados'=>'required'
      ]);
      if($validate->fails()){
        return response()->json($validate->errors(),400);
      }

      //Actualizar el detallefactura
      $detallefactura = DetalleFactura::where('id',$id)->update($params_array);
      $data = array(
        'detallefactura'=>$params,
        'status'=> 'success',
        'code' => 200
      );

    }else{
      //devolver errors
      $data= array(
        'message'=>'Error de Login',
        'status'=> 'error',
        'code'=>300
      );
    }
    return response()->json($data,200);
  }

  public function destroy($id,Request $request){
    $hash = $request ->header('Authorization',null);
    $jwtAuth = new JwtAuth();
    $checkToken = $jwtAuth->checkToken($hash);
    if($checkToken){
      //comprobar que existe el registro
      $detallefactura = DetalleFactura::find($id);
      // borrarlo
      $detallefactura-> delete();
      //Devolverlo
      $data = array(
        'detallefactura'=>$detallefactura,
        'status'=> 'success',
        'code' => 200
      );
    }else{
      $data= array(
        'message'=>'Login incorrecto',
        'status'=> 'error',
        'code'=>400
      );
    }
    return response()->json($data,200);
  }
}
