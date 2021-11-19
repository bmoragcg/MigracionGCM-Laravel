<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
  use HasFactory;

  protected $table = "crm_cargos_empleados";

  protected $primaryKey = 'car_id';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['car_id', 'car_nombre'];
}
