<?php
namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Cliente;


class ClienteController extends Controller
{
  public function index(Request $request){
    $clientes = Cliente::all();//->load('user');
    return response()->json(array(
      'clientes'=> $clientes,
      'status'=>'success'
    ),200);
  }

  public function show($id){
    $cliente = Cliente::find($id);
    return response()->json(array(
      'cliente'=>$cliente,
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
        'cedula'=>'required',
        'nombre'=>'required|max:25',
        'apellido'=>'required|max:25',
        'telefono'=>'required|max:10'
      ]);
      if($validate->fails()){
        return response()->json($validate->errors(),400);
      }
      //guardar clientes
      $cliente = new Cliente();
      //$cliente->id = $params->id;
      $cliente->cedula = $params->cedula;
      $cliente->nombre = $params->nombre;
      $cliente->apellido = $params->apellido;
      $cliente->telefono = $params->telefono;
      $cliente->save();
      $data= array(
        'cliente'=>$cliente,
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
        'cedula'=>'required',
        'nombre'=>'required|max:25',
        'telefono'=>'required|max:10',
        'apellido'=>'required|max:25',
      ]);
      if($validate->fails()){
        return response()->json($validate->errors(),400);
      }

      //Actualizar el cliente
      $cliente = Cliente::where('id',$id)->update($params_array);
      $data = array(
        'cliente'=>$params,
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
      $cliente = Cliente::find($id);

      // borrarlo
      $cliente-> delete();
      //Devolverlo
      $data = array(
        'cliente'=>$cliente,
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
