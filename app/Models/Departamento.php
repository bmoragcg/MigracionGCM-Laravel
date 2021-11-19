<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
  use HasFactory;

  protected $table = "crm_departamentos";

  protected $primaryKey = 'dep_id';
  protected $keyType = 'string';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ["dep_id", "dep_indicativo", "dep_nombre", "dep_lat", "dep_lng", "pais_id", "region_id"];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = ["dep_indicativo", "dep_lat", "dep_lng", "pais_id", "region_id"];

  public function region()
  {
    return $this->belongsTo('App\Models\Region', 'region_id');
  }
}
