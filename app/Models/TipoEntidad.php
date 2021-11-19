<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEntidad extends Model
{
  use HasFactory;

  protected $table = "crm_tipo_entidad";

  protected $primaryKey = 'tpe_id';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   * @var array
   */
  protected $fillable = ['tpe_id', 'tpe_nombre', 'tpe_sigla'];
}
