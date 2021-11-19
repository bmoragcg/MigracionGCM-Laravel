<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoEmpleado extends Model
{
  // use HasFactory;
  protected $table = 'crm_permisos_empleados';
  public $timestamps = false;

  protected $primaryKey = 'per_id';

  protected $fillable = ['per_id', 'mod_id', 'emp_username'];
}
