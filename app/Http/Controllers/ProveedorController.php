<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Proveedor;

class ProveedorController extends Controller
{
  public function index(Request $request){
    $proveedores = Proveedor::all();//->load('user');
    return response()->json(array(
      'proveedores'=> $proveedores,
      'status'=>'success'
    ),200);
  }

  public function show($id){
    $proveedor = Proveedor::find($id);
    if(is_object($proveedor)){
      return response()->json(array('proveedor'=>$proveedor,
        'status'=>'success'),200);
    }else{
      return response()->json(array('message'=>"El proveedor no existe",
        'status'=>'success'),200);
    }

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
        'nit'=>'required',
        'nombre'=>'required|max:25',
        'telefono'=>'required|max:10',
        'direccion'=>'required',
      ]);
      if($validate->fails()){
        return response()->json($validate->errors(),400);
      }
      //guardar proveedores
      $proveedor = new Proveedor();
      //$proveedor->id = $params->id;
      $proveedor->nit = $params->nit;
      $proveedor->nombre = $params->nombre;
      $proveedor->telefono = $params->telefono;
      $proveedor->direccion = $params->direccion;
      $proveedor->save();
      $data= array(
        'proveedor'=>$proveedor,
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
        'nit'=>'required',
        'nombre'=>'required|max:25',
        'telefono'=>'required|max:10',
        'direccion'=>'required',
      ]);
      if($validate->fails()){
        return response()->json($validate->errors(),400);
      }

      //Actualizar el proveedor
      unset($params_array['id']);
      unset($params_array['created_at']);
      $proveedor = Proveedor::where('id',$id)->update($params_array);
      $data = array(
        'proveedor'=>$params,
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
      $proveedor = Proveedor::find($id);

      // borrarlo
      $proveedor-> delete();
      //Devolverlo
      $data = array(
        'proveedor'=>$proveedor,
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
