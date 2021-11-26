<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
use App\Models\EntidadNegocios;




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


  public function dataToString($arr)
  {
    $elms = array();
    foreach ($arr as $object) {
      $elms[] = "'" . $object . "'";
    }

    return join(',', $elms);
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
        'entidad.ent_cartera' => 'string',
        'entidad.ent_cartera_condicion' => 'string',
        'entidad.org_id' => 'string',
        'entidad.tpe_id' => 'string',
        'entidad.nivel_supervision' => 'string',
        'entidad.comercial' => 'array',
        'entidad.comercialNegocio' => 'array',
        'entidad.cotizacion' => 'array',
        'entidad.tipoCliente' => 'array',
        'entidad.tipoNegocio' => 'array',
        // 'entidad.tipoNegocioEntrenamiento' => 'number',
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
      // array_merge(['crm_entidades.*'],[])
      $ent = Entidad::with('sucursalPrincipal')->select('crm_entidades.*')->distinct('crm_entidades.ent_nit')
        ->join('crm_sucursales', 'crm_entidades.ent_nit', '=', 'crm_sucursales.ent_nit')
        ->join('crm_empleados', 'crm_sucursales.suc_id', '=', 'crm_empleados.suc_id');

      if (!empty($validatedData)) {
        if (!empty($validatedData['sucursal'])) {
          foreach ($validatedData['sucursal'] as $key => $value) {

            // print_r($value);

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
            if (
              $key !== 'comercial'
              && $key !== 'comercialNegocio'
              && $key !== 'cotizacion'
              && $key !== 'ent_cartera'
              && $key !== 'tipoCliente'
              && $key !== 'tipoNegocio'
            ) {
              $ent->where("crm_entidades." . $key, 'like', "%{$value}%");
            }
          }
        };
      }


      if (!empty($validatedData['entidad']['comercial'])) {

        // $comerciales =  $this->dataToString($validatedData['entidad']['comercial']);

        if (!empty($validatedData['entidad']['cotizacion'])) {
          $ent->join("crm_agenda_citas AS c", "crm_entidades.ent_nit", "c.ent_nit")
            ->join("crm_agendas AS a", "c.age_id", "a.age_id");

          $ent->whereIn("a.emp_username", $validatedData['entidad']['comercial']);


          $comercialCotizacion = $this->dataToString($validatedData['entidad']['cotizacion']);

          switch ($comercialCotizacion) {
            case "'Cobertura'":
              $ent->join("crm_cotizaciones_cobertura AS ccb", "c.aci_id", "ccb.aci_id");
              break;

            case "'Consultoria'":
              $ent->join("crm_cotizacion_consultoria AS ccs", "c.aci_id", "ccs.aci_id");
              break;

            default:

              $ent->leftJoin("crm_cotizaciones_cobertura AS ccb", "c.aci_id", "ccb.aci_id")
                ->leftJoin("crm_cotizacion_consultoria AS ccs", "c.aci_id", "ccs.aci_id")
                ->where("c.aci_visitado", "=", 2)->whereRaw('ccb.aci_id IS NOT NULL OR ccs.aci_id IS NOT NULL');

              // $ent->where("c.aci_visitado", "=", 2)->whereNotNull("ccb.aci_id")->orWhereNotNull("ccs.aci_id");
          }
        } else {
          $ent->whereIn("crm_entidades.emp_username", $validatedData['entidad']['comercial']);
        }
      }

      if (!empty($validatedData['entidad']['comercialNegocio'])) {

        $ent->joinSub(function ($query) use ($validatedData) {
          $query->from("crm_entidad_negocios")->select("ent_nit")->whereIn("emp_username", $validatedData['entidad']['comercialNegocio']);
        }, 'cml', function ($join) {
          $join->on("crm_entidades.ent_nit", "cml.ent_nit");
        });


        // print_r($comercialNegocio);
        // $ent->join(EntidadNegocios::raw("(SELECT ent_nit FROM crm_entidad_negocios)"));
        // $ent->join(EntidadNegocios::raw('(SELECT ent_nit FROM crm_entidad_negocios WHERE emp_username IN ({$comercialNegocio})) cml ON crm_entidades.ent_nit = cml.ent_nit'));
      }


      // print_r($validatedData['entidad']['ent_cartera_condicion']);


      if ((isset($validatedData['entidad']['ent_cartera_condicion'])
          && $validatedData['entidad']['ent_cartera_condicion'] !== '0')
        && (isset($validatedData['entidad']['ent_cartera']) && !empty(trim($validatedData['entidad']['ent_cartera'])))
      ) {


        // print_r("Entre hp");


        $signo = "";
        $ent_cartera = intval($validatedData['entidad']['ent_cartera']);

        // print_r(gettype($ent_cartera));

        switch ($validatedData['entidad']['ent_cartera_condicion']) {

          case '1':
            $signo = ">";
            break;
          case '2':
            $signo = "<";
            break;
          case '3':
            $signo = ">=";
            break;
          case '4':
            $signo = "<=";
            break;
          default:
            $signo = "=";
            break;
        }
        $ent->where("crm_entidades.ent_cartera", "$signo", $ent_cartera);
      }

      /** Variables para tipo de cliente y tipo de negocio */
      $typeJoin = "";
      $campos = [];

      // print_r("entro al for");

      // print_r($validatedData['entidad']['tipoNegocio']);



      if (!empty($validatedData['entidad']['tipoNegocio'])) {
        for ($i = 0; $i < count($validatedData['entidad']['tipoNegocio']); $i++) {


          if ($validatedData['entidad']['tipoNegocio'][$i] == 4) {
            if (count($validatedData['entidad']['tipoNegocio']) > 1) {
              // $campos = [',entre.cur_ent AS curso_ent ,', 'entre.cur_nombre,', 'entre.cur_fecha,', 'entre.lugar AS cur_lugar'];

              // print_r("entro al left");

              // $ent->leftJoinSub(function ($query) {

              //   $query->from("crm_cursos_entidad")->select('crm_cursos_entidad.ent_nit AS cur_ent , group_concat(crm_cursos.cur_nombre) AS cur_nombre,group_concat(crm_cursos_entidad.fecha_curso) AS cur_fecha , group_concat(crm_paises.pai_nombre , ' - ' , crm_departamentos.dep_nombre , ' - ' , crm_ciudades.ciu_nombre separator ' | ') AS lugar')->join("crm_cursos", "crm_cursos_entidad.cur_id", "crm_cursos.cur_id")->join("crm_paises", "crm_cursos_entidad.pai_id", "crm_paises.pai_id")->join("crm_departamentos", "crm_cursos_entidad.dep_id", "crm_departamentos.dep_id")->join("crm_ciudades", "crm_cursos_entidad.ciu_id", "crm_ciudades.ciu_id")->groupBy('ent_nit');
              // }, 'entre', function ($left) {
              //   $left->on("crm_entidades.ent_nit", "entre.ent_nit");
              // });

              // $ent->joinSub(function ($query) use ($validatedData) {
              //   $query->from("crm_entidad_negocios")->select("ent_nit")->whereIn("emp_username", $validatedData['entidad']['comercialNegocio']);
              // }, 'cml', function ($join) {
              //   $join->on("crm_entidades.ent_nit", "cml.ent_nit");
              // });


              $ent->leftJoinSub(function ($query) {

                $query->from("crm_cursos_entidad AS cur_ent")->select("cur_ent.ent_nit AS cur_ent, group_concat(cur.cur_nombre) AS cur_nombre, group_concat(cur_ent.fecha_curso) AS cur_fecha,group_concat(pai.pai_nombre,'-',dep.dep_nombre,'-',ciu.ciu_nombre separator '|') AS lugar")->join("crm_cursos AS cur", "cur_ent.cur_id", "cur.cur_id")->join("crm_paises AS pai", "cur_ent.pai_id", "pai.pai_id")->join("crm_departamentos AS dep", "cur_ent.dep_id", "dep.dep_id")->join("crm_ciudades AS ciu",  "cur_ent.ciu_id", "ciu.ciu_id")->groupBy('ent_nit');
              }, 'prueba', function ($leftJoin) {
                $leftJoin->on("crm_entidades.ent_nit", "prueba.ent_nit");
              });
            } else {

              print_r("entro al inner join");

              $campos = [',entre.cur_ent AS curso_ent ,', 'entre.cur_nombre,', 'entre.cur_fecha,', 'entre.lugar AS cur_lugar'];
              $ent->joinSub(function ($query) {
                $query->from("crm_cursos_entidad AS cur_ent")->select("cur_ent.ent_nit AS cur_ent, group_concat(cur.cur_nombre) AS cur_nombre, group_concat(cur_ent.fecha_curso) AS cur_fecha,group_concat(pai.pai_nombre,'-',dep.dep_nombre,'-',ciu.ciu_nombre separator '|') AS lugar")->join("crm_cursos AS cur", "cur_ent.cur_id", "cur.cur_id")->join("crm_paises AS pai", "cur_ent.pai_id", "pai.pai_id")->join("crm_departamentos AS dep", "cur_ent.dep_id", "dep.dep_id")->join("crm_ciudades AS ciu", "cur_ent.ciu_id", "ciu.ciu_id")->groupBy('ent_nit');
              }, 'entre', function ($join) {
                $join->on("crm_entidades.ent_nit", "entre.cur_ent");
              });
            }
          }
        }
      }



      // if (!empty($validatedData['entidad']['comercialNegocio'])) {
      //   $comerciaNegocio =  $this->dataToString($validatedData['entidad']['comercialNegocio']);
      // }



      // (!empty($datos->selectComercialCotizacion)){
      //   $ent->join("crm_agenda_citas c", "ent.ent_nit", "c.ent_nit")
      //   ->join()};


      return response()->json($ent->get());
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
