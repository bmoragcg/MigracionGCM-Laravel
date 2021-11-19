<?php

namespace App\Http\Controllers;

use App\Empleado as AppEmpleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

// use App\Models\Empleado2;
use App\Models\Empleado;

// use app\Empleado;

use App\Models\Modulo;


class LoginController extends Controller
{
  //

  public function login()
  {
    $usuario = $this->decodificar($_GET['u']);
    $emp = Empleado::where('emp_username', $usuario)->first();

    if ($emp) {
      $token = JWTAuth::fromUser($emp);
      JWTAuth::setToken($token);
      return $this->respondWithToken($token);
    }
  }

  protected function respondWithToken($token)
  {
    $resUser = JWTAuth::getPayload($token)->toArray()['user'];

    $destino = Modulo::find($resUser->emp_pagina_ini);

    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      // 'expires_in' => Auth()->factory()->getTTL() * 6000
      'expires_in' => auth('api')->factory()->getTTL() * 60,
      'user' => $resUser,
      'destino' => $destino->mod_link
    ]);
  }

  public function decodificar($cifrado)
  {
    //Se decodifica el texto enviado
    $decode = base64_decode($cifrado);

    //Se separa en un array el texto de la salida
    $separador = explode("Ñ", $decode);

    //Se decodifica el texto
    $usuario = base64_decode($separador[1]);

    return $usuario;
  }
}
