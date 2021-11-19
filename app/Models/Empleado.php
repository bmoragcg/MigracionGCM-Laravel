<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Empleado extends Authenticatable implements JWTSubject
{
  use Notifiable;

  protected $table = "crm_empleados";

  protected $primaryKey = 'emp_username';
  protected $keyType = 'string';
  public $timestamps = false;
  // select * from `crm_sucursales` where `crm_sucursales`.`empleado_emp_username` = mvasquez and `crm_sucursales`.`empleado_emp_username` is not null
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'emp_username', 'emp_password', 'emp_pnombre', 'emp_papellido', 'emp_snombre', 'emp_sapellido', 'emp_identificacion', 'emp_cargo_old',
    'emp_cargo', 'emp_sexo', 'suc_id', 'emp_mes_cumpleanios', 'emp_dia_cumpleanios', 'red_id', 'emp_salario', 'emp_estado', 'emp_estado_ent', 'emp_tipo_usuario',
    'emp_pagina_ini', 'emp_fecha_creacion', 'emp_fecha_actualizacion', 'emp_usuario_actualizacion', 'emp_acceso_api', 'emp_token', 'emp_token_device', 'emp_logueado',
    'emp_ultimo_logueo', 'emp_extension', 'emp_call'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'emp_password', 'emp_identificacion', 'emp_cargo_old', 'emp_cargo', 'emp_sexo', 'suc_id', 'emp_mes_cumpleanios', 'emp_dia_cumpleanios',
    'red_id', 'emp_salario', 'emp_estado_ent', 'emp_tipo_usuario', 'emp_fecha_creacion', 'emp_fecha_actualizacion',
    'emp_usuario_actualizacion', 'emp_acceso_api', 'emp_token', 'emp_token_device', 'emp_logueado', 'emp_ultimo_logueo', 'emp_extension', 'emp_call'
  ];

  public function sucursal()
  {
    return $this->belongsTo('App\Models\Sucursal', 'suc_id')->with('pais')->with('departamento')->with('ciudad');
  }

  public function red()
  {
    return $this->belongsTo('App\Models\Red', 'red_id');
  }

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return ['user' => $this];
  }

  // default
  protected static function boot()
  {
    parent::boot();

    static::creating(function ($query) {
      $query->emp_password = '';
      $query->emp_cargo_old = '';
      $query->emp_salario = 0;
      $query->emp_estado = 0;
      $query->emp_estado_ent = 1;
      $query->emp_tipo_usuario = 0;
      $query->emp_pagina_ini = 0;
      $query->emp_fecha_creacion = date('Y-m-d H:i:s');
      $query->emp_fecha_actualizacion = date('Y-m-d H:i:s', mktime(0, 0, 0, 0, 0, 0000));
      $query->emp_acceso_api = 0;
      $query->emp_token = '';
      $query->emp_token_device = '';
      $query->emp_logueado = 0;
      $query->emp_ultimo_logueo = date('Y-m-d H:i:s', mktime(0, 0, 0, 0, 0, 0000));
      $query->emp_extension = '';
      $query->emp_call = 0;
    });
  }
}
