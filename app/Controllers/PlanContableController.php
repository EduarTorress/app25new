<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Bancos;
use App\Models\Marca;
use App\Models\NumerosCuenta;
use App\Models\PlanesContables;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class PlanContableController extends Controller
{
   private $pc;
   function __construct()
   {
      $middlweare = new AuthAdminMiddleware(['index']);
      $this->registerMiddleware($middlweare);
      $this->pc = new PlanesContables();
   }
   function index()
   {
      return \view('planescontables/index', ['titulo' => 'Planes Contables']);
   }
   function lista(Request $request)
   {
      $modo = $request->get('modo');
      $busqueda = "%" . $request->get('cbuscar') . "%";
      if ($modo == 'numero') {
         $lista = $this->pc->listartodosxnumerocuenta($busqueda);
      } else {
         $lista = $this->pc->listartodosxnombre($busqueda);
      }
      return view('planescontables/listaplanescontables', ['lista' => $lista]);
   }
   function getctabynro(Request $request)
   {
      if (empty($request->get('cbuscar'))) {
         $data = [
            'data' => [],
            'estado' => '0'
         ];
         return response()->json($data, 200);
      }
      $busqueda = "%" . $request->get('cbuscar') . "%";
      $lista = $this->pc->listartodosxnumerocuenta($busqueda);
      $data = [
         'data' => $lista[0],
         'estado' => '1'
      ];
      return response()->json($data, 200);
   }
   function create()
   {
      $titulo = 'Registrar Plan Contable';
      $listarcuentasd = $this->pc->listarcuentasdestinod();
      $listarcuentash = $this->pc->listarcuentasdestinoh();
      return view('planescontables/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0, 'listarcuentasd' => $listarcuentasd, 'listarcuentash' => $listarcuentash]);
   }
   function edit($id)
   {
      $titulo = 'Editar Plan Contable';
      $data = $this->pc->buscarid($id);
      $listarcuentasd = $this->pc->listarcuentasdestinod();
      $listarcuentash = $this->pc->listarcuentasdestinoh();
      return view('planescontables/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $data, 'id' => $id, 'listarcuentasd' => $listarcuentasd, 'listarcuentash' => $listarcuentash]);
   }
   function store(Request $request)
   {
      $validator = new Validator($request->getBody());
      $validator->rule('required', 'txtnrocuenta')->message('El N° de Cuenta es Obligatorio');
      $validator->labels([
         'nombre' => 'txtnrocuenta'
      ]);
      if (!$validator->validate()) {
         $data = [
            'message' => 'Errores de validacion',
            'errors' => $validator->errors()
         ];
         return response()->json($data, 422);
      }
      try {
         $pc = new PlanesContables();
         $pc->nrocuenta = $request->get('txtnrocuenta');
         $pc->txtnombre = $request->get('txtnombre');
         $pc->txtoperacion = $request->get('txtoperacion');
         $pc->txtcuentadestinodebe = $request->get('txtcuentadestinodebe');
         $pc->txtcuentadestinohaber = $request->get('txtcuentadestinohaber');
         $pc->txtcodigosunat = $request->get('txtcodigosunat');
         $pc->cmbtipocta = $request->get('cmbtipocta');
         if ($pc->save()) {
            return response()->json(['message' => 'Plan Contable registrado correctamente'], 201);
         } else {
            return response()->json(['message' => 'Error al registrar Plan Contable'], 400);
         }
      } catch (\Exception $error) {
         return response()->json(['message' => 'Error al registrar Plan Contable ' . $error], 500);
      }
   }
   public function update($id, Request $request)
   {
      $validator = new Validator($request->getBody());
      $validator->rule('required', 'txtnrocuenta')->message('El N° de Cuenta es Obligatorio');
      $validator->labels([
         'nombre' => 'txtnrocuenta'
      ]);
      if (!$validator->validate()) {
         $data = [
            'message' => 'Errores de validacion',
            'errors' => $validator->errors()
         ];
         return response()->json($data, 422);
      }

      try {
         $pc = new PlanesContables();
         $pc->idplan = $id;
         $pc->nrocuenta = $request->get('txtnrocuenta');
         $pc->nrocuenta = $request->get('txtnrocuenta');
         $pc->txtnombre = $request->get('txtnombre');
         $pc->txtoperacion = $request->get('txtoperacion');
         $pc->txtcuentadestinodebe = $request->get('txtcuentadestinodebe');
         $pc->txtcuentadestinohaber = $request->get('txtcuentadestinohaber');
         $pc->txtcodigosunat = $request->get('txtcodigosunat');
         $pc->cmbtipocta = $request->get('cmbtipocta');
         if ($pc->update()) {
            return response()->json(['message' => 'Actualizado correctamente'], 200);
         } else {
            return response()->json(['message' => 'Error al actualizar'], 400);
         }
      } catch (\Exception $error) {
         return response()->json(['message' => 'Ocurrió un error ' . $error], 500);
      }
   }
}
