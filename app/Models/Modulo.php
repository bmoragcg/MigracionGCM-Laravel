<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
  // use HasFactory;
  protected $table = 'crm_modulos';
  protected $primaryKey = 'mod_id';

  protected $fillable = ['mod_id', 'mod_nombre', 'mod_icono', 'mod_link', 'mod_parent', 'mod_orden', 'mod_show'];

  /**
   * Get the comments for the blog post.
   */
  public function permisos()
  {
    return $this->hasMany('App\PermisoModulo', 'mod_id');
  }
}
