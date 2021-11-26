<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadNegocios extends Model
{
  use HasFactory;


  protected $table = "crm_entidad_negocios";

  protected $primaryKey = 'ent_nit';

  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['ent_nit', 'uni_id', 'exn_negocio', 'emp_username', 'exn_estado', 'fecha_operacion', 'fecha_inicio'];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = ['ent_ruc'];
}
