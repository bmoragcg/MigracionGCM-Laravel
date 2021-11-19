<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
  use HasFactory;

  protected $table = "crm_comunas";

  protected $primaryKey = 'com_id';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['com_id', 'com_nombre', 'com_lat', 'com_lng', 'ciu_id'];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = ['com_lat', 'com_lng', 'ciu_id'];
}
