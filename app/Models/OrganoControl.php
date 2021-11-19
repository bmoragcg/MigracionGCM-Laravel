<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganoControl extends Model
{
  use HasFactory;

  protected $table = 'crm_organo_control';
  public $primaryKey = 'org_id';
  public $timestamps = false;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'org_id', 'org_nombre', 'pai_id'
  ];
}
