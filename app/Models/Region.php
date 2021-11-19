<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
  use HasFactory;

  protected $table = "crm_regiones";

  protected $primaryKey = 'region_id';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   * @var array
   */
  protected $fillable = ['region_id', 'region_nombre'];
}
