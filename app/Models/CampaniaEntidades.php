<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaniaEntidades extends Model
{
  use HasFactory;

  protected $table = "crm_cc_campania_entidades";

  protected $primaryKey = 'id_campania';
  protected $keyType = 'string';
  public $timestamps = false;


  protected $fillable = ['id_campania', 'nombre', 'fecha_inicial', 'fecha_final'];

  protected $hidden = ['fecha_inicial', 'fecha_final'];
}
