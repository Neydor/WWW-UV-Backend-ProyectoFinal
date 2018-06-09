<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{

  public $key;

  public function __construct(){
    $this->key = 'claveSecreta789456123';
  }

  public function login($correo,$contrasena,$getToken=null){
    $user =User::where(
      array(
        'correo'=>$correo,
        'contrasena'=> $contrasena
      ))->first();//devuelve el primer objeto que retorne

    if(is_object($user)){
      //Generar el token
      $token = array(
        'sub'=>$user->id,
        'correo' =>$user->correo,
        'nombre'=>$user->nombre,
        'apellido'=>$user->apellido,
        'cargo'=>$user->cargo,
        'estado'=>$user->estado,
        'iat'=>time(),
        'expiracion'=>time()+ (24*60*60)
      );

    // validacion estado
      if($user->estado == '0'){
        return array('status'=>'error',
        'message'=>'Login no posible por cuenta desactivada');
      }

      $jwt=JWT::encode($token,$this->key,'HS256');
      $decoded=JWT::decode($jwt,$this->key,array('HS256'));

      if(is_null($getToken)){
        return $jwt;
      }else {
        return $decoded;
      }

    }else{
      //Error o credenciales malas
      return array('status'=>'error',
      'message'=>'Login ha fallado!!!!!');
    }

  }
  public function checkToken($jwt,$getIdentidad =false){
    $auth=false;
    try{
      $decoded = JWT::decode($jwt,$this->key,array('HS256'));

    }catch(\UnexpectedValueException $e){
      $auth=false;
    }catch(\DomainException $e){
      $auth=false;
    }

    if(isset($decoded) && is_object($decoded) && isset($decoded->sub)){
      $auth=true;
    }else{
      $auth=false;
    }

    if($getIdentidad){
      return $decoded;
    }
    return $auth;
  }

}
