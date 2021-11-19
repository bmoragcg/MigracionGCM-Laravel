<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
  use HasFactory;

  protected $table = "crm_ciudades";

  protected $primaryKey = 'ciu_id';
  protected $keyType = 'string';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['ciu_id', 'ciu_nombre', 'ciu_lat', 'ciu_lng', 'dep_id'];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = ['ciu_lat', 'ciu_lng', 'dep_id'];
}
