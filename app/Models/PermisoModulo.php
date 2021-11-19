<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoModulo extends Model
{
  // use HasFactory;

  protected $table = 'crm_permisos_mudulo';
  public $timestamps = false;

  protected $primaryKey = 'per_id';

  protected $fillable = ['per_id', 'per_nombre', 'mod_id'];
}
