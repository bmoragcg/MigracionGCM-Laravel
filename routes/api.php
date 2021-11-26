<?php

use App\Http\Controllers\BuscarEntidadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use app\Http\Controllers\LoginController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// $router->get('login', [LoginController::class, 'login']);

Route::get('login', [LoginController::class, 'login']);

Route::prefix('menu')->group(function () {
  Route::get('crearMenu',  [MenuController::class, 'crearMenu']);
});



Route::prefix('buscarEntidad')->group(function () {
  Route::get('', [BuscarEntidadController::class, 'index']);
  Route::get('dataByPai/{pai}', [BuscarEntidadController::class, 'dataByPai']);
  Route::get('dataByDep/{dep}', [BuscarEntidadController::class, 'dataByDep']);
  Route::get('dataByCiu/{ciu}', [BuscarEntidadController::class, 'dataByCiu']);
  Route::get('dataByOrg/{org}', [BuscarEntidadController::class, 'dataByOrg']);
  Route::post('filterData', [BuscarEntidadController::class, 'filterData']);
});

// Route::prefix('buscarEntidad')->group(function () {
//   Route::get('',  [BuscarEntidadController::class, 'index']);
// });

// Route::prefix('buscarEntidad')->group(function () {
//   Route::get('', [BuscarEntidadController::class, 'index']);
//   Route::get('dataByPai/{pai}', [BuscarEntidadController::class, 'dataByPai']);
// });


// $router->group(['prefix' => 'menu'], function () use ($router) {
//   $router->get('crearMenu', [MenuController::class, 'crearMenu']);
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });
