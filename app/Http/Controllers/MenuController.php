<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Modulo;


class MenuController extends Controller
{

  public function __construct()
  {
    $this->middleware('jwt');
  }

  public function crearMenu()
  {

    $parent = array();
    $menu = Modulo::where('mod_link', '#')->where('mod_parent', 0)->orderBy('mod_orden')->get();

    for ($i = 0; $i < count($menu); $i++) {
      $menu[$i]->parent = $this->get_menu_crm($menu[$i]->mod_id);
    }

    return response()->json(['menu' => $menu], 200);
  }

  public function get_menu_crm($mod, $nivel = 1)
  {
    $content = "";
    $user = auth()->user()->emp_username;


    $hijos = $this->getModulosHijos($user, $mod);

    for ($i = 0; $i < count($hijos); $i++) {
      if ($hijos[$i]->mod_link === '#') {
        $hijos2 = $this->getModulosHijos($user, $hijos[$i]->mod_id);

        if (count($hijos2) === 0) {
          unset($hijos[$i]);
        } else {
          $hijos[$i]->parent = $hijos2;
        }
      }
    }



    return $hijos;
  }

  public function getModulosHijos($user, $mod)
  {
    $hijos = DB::select("SELECT m.mod_id, m.mod_nombre, m.mod_icono, m.mod_link, m.mod_show, m.mod_parent FROM crm_modulos m
    LEFT JOIN crm_modulos_empleados u ON m.mod_id=u.mod_id
    WHERE (u.emp_username = '{$user}' AND m.mod_parent = {$mod}) OR (m.mod_link = '#' AND m.mod_parent = {$mod}) ORDER BY m.mod_orden ASC", [1]);

    return $hijos;
  }
}
