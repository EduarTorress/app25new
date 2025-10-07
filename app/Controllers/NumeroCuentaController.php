<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Bancos;
use App\Models\NumerosCuenta;
use App\Models\PlanesContables;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class NumeroCuentaController extends Controller
{
   private $nc;
   function __construct()
   {
      $middlweare = new AuthAdminMiddleware(['index']);
      $this->registerMiddleware($middlweare);
      $this->nc = new NumerosCuenta();
   }
   function index()
   {
      return \view('numeroscuenta/index', ['titulo' => 'Números de Cuenta']);
   }
   function lista(Request $request)
   {
      $lista = $this->nc->listar($request->get('cbuscar'));
      return view('numeroscuenta/listanumeroscuenta', ['lista' => $lista]);
   }
   function create()
   {
      $bancos = new Bancos();
      $listabancos = $bancos->listar('%%');
      $planescontables = new PlanesContables();
      $listaplanes = $planescontables->listarparanroscuenta();
      $titulo = 'Registrar Numero Cuenta';
      return view('numeroscuenta/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0, 'listabancos' => $listabancos, 'listaplanes' => $listaplanes]);
   }
   function edit($id)
   {
      $titulo = 'Editar Numero Cuenta';
      $data = $this->nc->buscarid($id);
      $bancos = new Bancos();
      $listabancos = $bancos->listar('%%');
      $planescontables = new PlanesContables();
      $listaplanes = $planescontables->listarparanroscuenta();
      return view('numeroscuenta/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $data, 'id' => $id, 'listabancos' => $listabancos, 'listaplanes' => $listaplanes]);
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
         $nc = new NumerosCuenta();
         $nc->nrocuenta = $request->get('txtnrocuenta');
         $nc->cmbbancos = $request->get('cmbbancos');
         $nc->cmbmoneda = $request->get('cmbmoneda');
         $nc->txtreferencia = $request->get('txtreferencia');
         $nc->cuentasociada = $request->get('cmbcuentasociada');

         if ($nc->save()) {
            return response()->json(['message' => 'Número de Cuenta registrada correctamente'], 200);
         } else {
            return response()->json(['message' => 'Error al registrar Numero de Cuenta'], 400);
         }
      } catch (\Exception $error) {
         return response()->json(['message' => 'Error al registrar Numero de Cuenta ' . $error], 500);
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
         $nc = new NumerosCuenta();
         // $idnc = $nc->buscarid($id);
         $nc->idcuenta = $id;
         $nc->nrocuenta = $request->get('txtnrocuenta');
         $nc->cmbbancos = $request->get('cmbbancos');
         $nc->cmbmoneda = $request->get('cmbmoneda');
         $nc->txtreferencia = $request->get('txtreferencia');
         $nc->cuentasociada = $request->get('cmbcuentasociada');
         if ($nc->update()) {
            return response()->json(['message' => 'Actualizado correctamente'], 200);
         } else {
            return response()->json(['message' => 'Error al actualizar'], 400);
         }
      } catch (\Exception $error) {
         return response()->json(['message' => 'Ocurrió un error ' . $error], 500);
      }
   }
}
