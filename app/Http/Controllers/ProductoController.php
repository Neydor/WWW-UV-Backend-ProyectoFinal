<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Producto;
use App\Proveedor;

class ProductoController extends Controller
{
  public function index(Request $request){
    $productos = Producto::all()->load('proveedores');
    return response()->json(array(
      'productos'=> $productos,
      'status'=>'success'
    ),200);
  }

  public function show($id){
    $producto = Producto::find($id);
    return response()->json(array(
      'producto'=>$producto,
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
      //$user = $jwtAuth->checkToken($hash,true);
      //validacion
      $validate = Validator::make($params_array,[
        'codigo'=>'required',
        'proveedores_id'=>'required|max:25',
        'nombre'=>'required|max:25',
        'precioUnidad'=>'required',
        'cantidad'=>'required',
        'presentacion'=>'required',
        'costoCompra'=>'required',
        'precioDocena'=>'required',
      ]);
      if($validate->fails()){
        return response()->json($validate->errors(),400);
      }
      //guardar productos
      $producto = new Producto();
      //$producto->id = $params->id;
      $proveedor = Proveedor::find($params->proveedores_id);
      if(is_null($proveedor)){
        $data= array(
          'message'=>'No existe el proveedor',
          'status'=> 'error',
          'code'=>300,
        );
        return response()->json($data,200);
      }
      $producto->codigo = $params->codigo;
      $producto->nombre = $params->nombre;

      $producto->proveedores_id = $params->proveedores_id;
      $producto->precioUnidad = $params->precioUnidad;
      $producto->cantidad = $params->cantidad;
      $producto->presentacion = $params->presentacion;
      $producto->costoCompra = $params->costoCompra;
      $producto->precioDocena = $params->precioDocena;
      $producto->save();
      $data= array(
        'producto'=>$producto,
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
        'codigo'=>'required',
        'proveedores_id'=>'required|max:25',
        'nombre'=>'required|max:25',
        'precioUnidad'=>'required',
        'cantidad'=>'required',
        'presentacion'=>'required',
        'costoCompra'=>'required',
        'precioDocena'=>'required',
      ]);
      if($validate->fails()){
        return response()->json($validate->errors(),400);
      }

      //Actualizar el producto
      $producto = Producto::where('id',$id)->update($params_array);
      $data = array(
        'producto'=>$params,
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
      $producto = Producto::find($id);

      // borrarlo
      $producto-> delete();
      //Devolverlo
      $data = array(
        'producto'=>$producto,
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
