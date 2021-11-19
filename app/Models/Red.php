<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Red extends Model
{
  use HasFactory;

  protected $table = "crm_redes";

  protected $primaryKey = 'red_id';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   * @var array
   */
  protected $fillable = ['red_id', 'red_nombre', 'red_orden'];
}
