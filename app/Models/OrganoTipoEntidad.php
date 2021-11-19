<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganoTipoEntidad extends Model
{
  use HasFactory;

  protected $table = "crm_organo_tipo_entidad";
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   * @var array
   */
  protected $fillable = ['tpe_id', 'org_id', 'impuesto', 'valor', 'intervalo'];

  /**
   * The attributes that should be hidden for arrays.
   * @var array
   */
  protected $hidden = ['intervalo'];


  /**
   * Get the comments for the blog post.
   */
  // public function sucursales()
  // {
  //     return $this->hasMany('App\Sucursal');
  // }

  public function organo_control()
  {
    return $this->belongsTo('App\Models\OrganoControl', 'org_id');
  }

  public function tipo_entidad()
  {
    return $this->belongsTo('App\Models\TipoEntidad', 'tpe_id');
  }
}
