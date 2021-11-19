<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelTipoEntidad extends Model
{
  use HasFactory;

  protected $table = 'crm_nivel_tipo_entidad';
  public $timestamps = false;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'nte_id', 'nte_nombre', 'tpe_id', 'org_id', 'files_sarl', 'files_sarl_opc'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'files_sarl', 'files_sarl_opc',
  ];


  public function organo_control()
  {
    return $this->belongsTo('App\Models\OrganoControl', 'org_id');
  }

  public function tipo_entidad()
  {
    return $this->belongsTo('App\Models\TipoEntidad', 'tpe_id');
  }
}
