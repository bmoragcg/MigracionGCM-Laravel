<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
  use HasFactory;

  protected $table = "crm_paises";

  protected $primaryKey = 'pai_id';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   * @var array
   */
  protected $fillable = ["pai_id", "pai_code1", "pai_code2", "pai_nombre", "pai_sim_moneda", "pai_nom_moneda", "pai_lat", "pai_lng"];

  /**
   * The attributes that should be hidden for arrays.
   * @var array
   */
  protected $hidden = ["pai_code1", "pai_code2", "pai_sim_moneda", "pai_nom_moneda", "pai_lat", "pai_lng"];


  /**
   * Get the comments for the blog post.
   */
  public function sucursales()
  {
    return $this->hasMany('App\Models\Sucursal');
  }
}
