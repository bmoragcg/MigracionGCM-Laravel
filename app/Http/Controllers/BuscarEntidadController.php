<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pais;
use App\Models\Empleado;
use App\Models\Cargo;
use App\Models\Red;
use App\Models\Departamento;
use App\Models\Ciudad;
use App\Models\Region;
use App\Models\Comuna;
use App\Models\OrganoControl;
use App\Models\OrganoTipoEntidad;
use App\Models\Entidad;
use App\Models\NivelTipoEntidad;
use App\Models\CampaniaEntidades;

class BuscarEntidadController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt');
  }

  public function index()
  {
    return response()->json([
      'paises' => Pais::all(),
      'comerciales' => Empleado::where('red_id', 1)->get(),
      'contactCenter' => Empleado::where('red_id', 11)->get(),
      'cargos' => Cargo::all(),
      'redes' => Red::all()
    ]);
  }

  // Consulta departamentos y organos de control de un pais
  public function dataByPai($pai)
  {
    $pai = explode(',',  $pai);
    return response()->json([
      'deptos' => Departamento::whereIn('pais_id', $pai)->get(),
      'organoControl' => OrganoControl::whereIn('pai_id', $pai)->get()
    ]);
  }

  // Retorna json con las ciudades de un departamento
  public function dataByDep($dep)
  {

    $dep = explode(',', $dep);
    return response()->json([
      'ciudades' => Ciudad::whereIn('dep_id', $dep)->get(),
      'departamentos' => Departamento::with('region')->find($dep)
    ]);
  }

  // Retorna json con las comunas de una ciudad
  public function dataByCiu($ciu)
  {
    return response()->json([
      'comunas' => Comuna::where('ciu_id', $ciu)->get()
    ]);
  }

  // Retorna json con todos los tipos de entidad de un organo de control
  public function dataByOrg($org)
  {
    return response()->json(['tipos' => OrganoTipoEntidad::with('tipo_entidad')->where("org_id", $org)->get()]);
  }

  /** Retorna json con los todos los niveles de un organo de control */
  public function nivelTipoEntidad($org, $tpe)
  {
    return response()->json([
      'niveles' => NivelTipoEntidad::with('organo_control')->with('tipo_entidad')->where('org_id', $org)->where('tpe_id', $tpe)->get
    ]);
  }

  public function filterData(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'sucursal.pai_id' => 'array',
        'sucursal.dep_id' => 'array',
        'sucursal.ciu_id' => 'array',
        'sucursal.com_id' => 'array',
        'entidad.ent_razon_social' => 'string',
        'entidad.ent_sigla' => 'string',
        'entidad.ent_nit' => 'string',
        'entidad.ent_ruc' => 'string',
        'entidad.ent_cartera' => 'int',
        'entidad.org_id' => 'string',
        'entidad.tpe_id' => 'string',
        'entidad.nivel_supervision' => 'string',
        'entidad.emp_username' => 'string',
        'empleado.emp_pnombre' => 'string',
        'empleado.emp_snombre' => 'string',
        'empleado.emp_papellido' => 'string',
        'empleado.emp_sapellido' => 'string',
        'empleado.emp_cargo' => 'string',
        'empleado.emp_sexo' => 'string',
        'empleado.emp_identificacion' => 'string',
        'empleado.emp_mes_cumpleanios' => 'string',
        'empleado.emp_dia_cumpleanios' => 'string',
        'empleado.red_id' => 'string',
        'mail.emp_correo' => 'string',
      ]);

      $ent = Entidad::with('sucursalPrincipal')->select('crm_entidades.*')->distinct('crm_entidades.ent_nit')
        ->join('crm_sucursales', 'crm_entidades.ent_nit', '=', 'crm_sucursales.ent_nit')
        ->join('crm_empleados', 'crm_sucursales.suc_id', '=', 'crm_empleados.suc_id');

      if (!empty($validatedData)) {
        if (!empty($validatedData['sucursal'])) {
          foreach ($validatedData['sucursal'] as $key => $value) {
            $ent->whereIn("crm_sucursales." . $key, $value);
          }
        };

        if (!empty($validatedData['empleado'])) {
          foreach ($validatedData['empleado'] as $key => $value) {
            $ent->where("crm_empleados." . $key, "like", "%$value%");
          }
        };

        if (!empty($validatedData['entidad'])) {
          foreach ($validatedData['entidad'] as $key => $value) {
            $ent->where("crm_entidades." . $key, 'like', "%{$value}%");
          }
        };
      }
      return response()->json($ent->paginate(10));
      // $results = Entidad::
    } catch (\Exception $e) {
      return response()->json(["err" => $e]);
    }
  }

  // asgina un comercial a todas las entidades que esten en la variable $this->objData->ents;
  public function asignarEntidad()
  {
    $where = "";
    $values = "";
    $ent = $this->objData->ents;
    for ($i = 0; $i < count($ent); $i++) {
      $where .= "'{$ent[$i]->ent_nit}',";
      if ($this->objData->campania !== '') {
        $values .= "({$this->objData->campania}, '{$ent[$i]->ent_nit}'),";
      }
    }

    $where = substr($where, 0, -1);
    $values = substr($values, 0, -1);


    $model = new Entidad();

    $model->__SET("emp_username", $this->objData->com);

    $r = $model->asignarEntidad($where);

    if ($this->objData->campania !== '') {
      $campania = new CampaniaEntidades();
      $r2 = $campania->insertMultiple($values);
    }
    echo json_encode($r);
  }

  public function guardarCampania()
  {
    $data = $this->objData;

    $model = new CampaniaEntidades();
    $model->__SET('nombre', $data->nombre);
    $model->__SET('fecha_inicial', (string) date("Y-m-d", strtotime($data->fechaIni)));
    $model->__SET('fecha_final', (string) date("Y-m-d", strtotime($data->fechaFin)));

    if ($model->insert()) {
      echo json_encode(array("ok" => true));
    } else {
      echo json_encode(array("ok" => false));
    };
  }

  public function getCampanias()
  {
    $model = new CampaniaEntidades();

    $data = $model->getAll();

    echo json_encode(array("campanias" => $data));
  }
}
