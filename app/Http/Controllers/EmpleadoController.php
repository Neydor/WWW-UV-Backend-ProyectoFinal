<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class EmpleadoController extends Controller
{
  public function register(Request $request){
    //Recoger las variables por post
    $json=$request->input('json',null);
    $params=json_decode($json);
    $cedula = (!is_null($json) && isset($params->cedula)) ? $params->cedula :null;
    $correo = (!is_null($json) && isset($params->correo)) ? $params->correo :null;
    $nombre = (!is_null($json) && isset($params->nombre)) ? $params->nombre :null;
    $apellido = (!is_null($json) && isset($params->apellido)) ? $params->apellido :null;
    $cargo = (!is_null($json) && isset($params->cargo)) ? $params->cargo :null;
    $direccion = (!is_null($json) && isset($params->direccion)) ? $params->direccion :null;
    $telefono = (!is_null($json) && isset($params->telefono)) ? $params->telefono :null;
    $estado = (!is_null($json) && isset($params->estado)) ? $params->estado :null;
    $contrasena = (!is_null($json) && isset($params->contrasena)) ? $params->contrasena :null;
      //echo "Accion registrar"; die();
      if(!is_null($correo) && !is_null($contrasena) && !is_null($nombre)){
        //crear el user
        $empleado = new User();
        $empleado->cedula = $cedula;
        $empleado->correo = $correo;
        $pwd=hash('sha256',$contrasena);
        $empleado->contrasena = $pwd;
        $empleado->nombre = $nombre;
        $empleado->apellido = $apellido;
        $empleado->cargo = $cargo;
        $empleado->direccion = $direccion;
        $empleado->telefono = $telefono;
        $empleado->estado = $estado;
        // comprobar user duplicado
        $isset_user=User::where('correo','=',$correo)->first();
        if(count($isset_user)==0){
          $empleado->save();
          $data=array(
            'status'=>'success',
            'code'=>200,
            'message'=>'Empleado registrado correctamente'
          );
        }else {
          $data=array(
            'status'=>'error',
            'code'=>200,
            'message'=>'Empleado duplicado'
          );
        }
      }else{
        $data=array(
          'status' => 'error',
          'code' => 400,
          'message' => 'Usuario no creado'
        );
      }
      return response()->json($data,200);
  }



  public function login(Request $request){
    $jwtAuth=new JwtAuth();
    //post
    $json=$request->input('json',null);
    $params = json_decode($json);
    $correo = (!is_null($json) && isset($params->correo))? $params->correo:null;
    $contrasena= (!is_null($json) && isset($params->contrasena))? $params->contrasena:null;
    $getToken = (!is_null($json) && isset($params->getToken)) ? $params->getToken:null;

    //Cifrar la $contrasena

    $pwd=hash('sha256',$contrasena);

    if(!is_null($contrasena) && !is_null($contrasena)&& ($getToken == null || $getToken=='false')){
      $login = $jwtAuth->login($correo,$pwd);
    }elseif($getToken!=null){
      //var_dump($getToken);die();
      $login = $jwtAuth->login($correo,$pwd,$getToken);
    }else{
        $login= array('status'=>'error',
    'message'=> 'envia tus datos por post');
    }
    return response()->json($login,200);
  }
  //
}
