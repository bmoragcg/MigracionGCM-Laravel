<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
  use HasFactory;

  protected $table = "crm_sucursales";

  protected $primaryKey = 'suc_id';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'suc_id', 'suc_nombre', 'ent_nit', 'pai_id', 'dep_id', 'ciu_id', 'com_id', 'suc_direccion', 'suc_telefono', 'suc_lat', 'suc_lng', 'suc_tipo',
    'suc_fecha_actualizacion', 'suc_usuario_actualizacion', 'ent_ruc',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'suc_lat', 'suc_lng',
    'suc_fecha_actualizacion', 'suc_usuario_actualizacion', 'ent_ruc',
  ];

  public function empleados()
  {
    return $this->hasMany('App\Models\Empleado', 'suc_id');
  }

  /**
   * Get the post that owns the comment.
   */
  public function entidad()
  {
    return $this->belongsTo('App\Models\Entidad', 'foreign_key', 'ent_nit');
  }

  public function pais()
  {
    return $this->belongsTo('App\Models\Pais', 'pai_id');
  }

  public function departamento()
  {
    return $this->belongsTo('App\Models\Departamento', 'dep_id');
  }

  public function ciudad()
  {
    return $this->belongsTo('App\Models\Ciudad', 'ciu_id');
  }

  public function comuna()
  {
    return $this->belongsTo('App\Models\Comuna', 'com_id');
  }

  // default
  protected static function boot()
  {
    parent::boot();

    static::creating(function ($query) {
      $query->ent_ruc = "";
      // $query->ent_correo = '';
    });
  }
}
