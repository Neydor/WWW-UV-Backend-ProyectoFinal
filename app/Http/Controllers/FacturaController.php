<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Factura;
use App\Cliente;

class FacturaController extends Controller
{
    public function index(Request $request){
      $facturas = Factura::all()->load('user')->load('cliente');
      return response()->json(array(
        'facturas'=> $facturas,
        'status'=>'success'
      ),200);
    }

    public function show($id){
      $factura = Factura::find($id)->load('user')->load('cliente');
      return response()->json(array(
        'factura'=>$factura,
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
          'clientes_id'=>'required',
          'total'=>'required',
          'estados'=>'required'
        ]);
        if($validate->fails()){
          return response()->json($validate->errors(),400);
        }
        //guardar facturas
        $factura = new Factura();
        //$factura->id = $params->id;
        $cliente = Cliente::find($params->clientes_id);
        if(is_null($cliente)){
          $data= array(
            'message'=>'No existe el cliente',
            'status'=> 'error',
            'code'=>300,
          );
          return response()->json($data,200);
        }
        $factura->clientes_id = $params->clientes_id;
        $factura->user_id = $user->sub;
        $factura->total = $params->total;
        $factura->estados = $params->estados;
        $factura->save();
        $data= array(
          'factura'=>$factura,
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

        //Actualizar el factura
        $factura = Factura::where('id',$id)->update($params_array);
        $data = array(
          'factura'=>$params,
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
        $factura = Factura::find($id);
        // borrarlo
        $factura-> delete();
        //Devolverlo
        $data = array(
          'factura'=>$factura,
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
