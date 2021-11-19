<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
  use HasFactory;

  protected $table = "crm_entidades";

  protected $primaryKey = 'ent_nit';
  protected $keyType = 'string';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'ent_nit', 'ent_razon_social', 'ent_sigla', 'ent_sitio_web', 'ent_num_asociados', 'ent_num_empleados', 'ent_fecha_aniversario',
    'ent_cartera', 'ent_activos', 'tpe_id', 'org_id', 'cte_id', 'ent_programado', 'nivel_supervision', 'ent_fecha_actualizacion', 'ent_usuario_actualizacion', 'ent_logo',
    'emp_username', 'ent_val', 'ent_ruc', 'ent_log_fecha', 'ent_log_username', 'ent_outsourcing', 'ent_ciiu', 'ent_correo', 'ent_cerrada'
  ];

  protected $hidden = [
    'ent_sitio_web', 'ent_num_asociados', 'ent_num_empleados', 'ent_fecha_aniversario',
    'tpe_id', 'org_id', 'cte_id', 'ent_programado', 'nivel_supervision', 'ent_fecha_actualizacion', 'ent_usuario_actualizacion', 'ent_logo',
    'ent_val', 'ent_log_fecha', 'ent_log_username', 'ent_outsourcing', 'ent_ciiu', 'ent_correo', 'ent_cerrada'
  ];

  public function empleado()
  {
    return $this->belongsTo('App\Models\Empleado', 'emp_username');
  }

  public function sucursales()
  {
    return $this->hasMany('App\Models\Sucursal', 'ent_nit')->with('empleados')->with('pais')->with('departamento')->with('ciudad')->with('comuna');
  }

  public function organoControl()
  {
    return $this->belongsTo('App\Models\OrganoControl', 'org_id');
  }

  public function tipoEntidad()
  {
    return $this->belongsTo('App\Models\TipoEntidad', 'tpe_id');
  }

  public function sucursalPrincipal()
  {
    return $this->hasMany('App\Models\Sucursal', 'ent_nit')->with('empleados')->with('pais')->with('departamento')->with('ciudad')->where('suc_tipo', 1);
  }

  public function getAllEntByLastGestion()
  {
    return $this->hasMany('App\Models\Sucursal', 'ent_nit')->with('empleados')->with('pais')->with('departamento')->with('ciudad')->where('suc_tipo', 1);
  }

  // default
  protected static function boot()
  {
    parent::boot();

    static::creating(function ($query) {
      $query->ent_programado = 0;
      $query->ent_correo = '';
    });
  }
}
