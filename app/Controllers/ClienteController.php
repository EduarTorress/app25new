<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Cliente;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class ClienteController extends Controller
{
    private $cliente;
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->cliente = new Cliente();
    }
    function index()
    {
        $titulo = 'Clientes';
        return view('admin/cliente/index', ['titulo' => $titulo]);
    }
    function buscar(Request $request)
    {
        $abuscar = trim($request->get('cbuscar'));
        $opt = intval($request->get("option"));
        $nid = 0;
        $lista = $this->cliente->buscarClientes($abuscar, $opt, $nid);
        $cmodo = $request->get("modo");
        return view('admin/cliente/tm_listaclientes', ['lista' => $lista, 'modo' => $cmodo]);
    }
    function seleccionado(Request $request)
    {
        $cliente = array();
        $cliente = array(
            'nombre' => $request->get("nombre"),
            'idcliente' => $request->get('idclie'),
            'ruc' => $request->get('ruc'),
            'txtdnicliente' => $request->get('txtdnicliente'),
            'txtdireccion' => $request->get('txtdireccion')
        );
        \session()->set('cliente', $cliente);
        return response()->json([
            'message' => 'Cliente Seleccionado  correctamente'
        ], 200);
    }
    function lista(Request $request)
    {
        $cbuscar = "%" . $request->get('cbuscar') . "%";
        $lista = $this->cliente->listar($cbuscar);
        return view('admin/cliente/listaclientes', ['lista' => $lista]);
    }
    function listarremitentes($cbuscar)
    {
        $lista = $this->cliente->listar($cbuscar);
        return $lista;
    }
    function create()
    {
        $titulo = 'Registrar cliente';
        return view('admin/cliente/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $titulo = 'Editar cliente';
        $destinarario = $this->cliente->buscarid($id);
        return view('admin/cliente/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $destinarario, 'id' => $id]);
    }
    function store(Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtNombre')->message('El nombre del Cliente es obligatorio');
        $validator->labels([
            'nombre' => 'txtnombre'
        ]);
        if (!$validator->validate()) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => $validator->errors()
            ];
            return response()->json($data, 422);
        }
        $existe = "F";
        try {
            $cliente = new Cliente();
            $cliente->txtRUC = $request->get('txtRUC');
            $cliente->txtDNI = $request->get('txtDNI');
            $cliente->txtNombre = $request->get('txtNombre');
            $cliente->txtDireccion = $request->get('txtDireccion');
            $cliente->txtCiudad = $request->get('txtCiudad');
            $cliente->txtUbigeo = $request->get('cmbUbigeo');
            if (!empty($request->get('txtRUC'))) {
                $existe = $cliente->consultarclientexruc($request->get('txtRUC'));
                if ($existe == "T") {
                    return response()->json(['message' => 'Cliente ya existente.'], 400);
                }
            }
            if (!empty($request->get('txtDNI'))) {
                $existe = $cliente->consultarclientexdni($request->get('txtDNI'));
                if ($existe == "T") {
                    return response()->json(['message' => 'Cliente ya existente.'], 400);
                }
            }
            if (!empty($request->get('txtNombre'))) {
                $existe = $cliente->consultarclientexrazon($request->get('txtNombre'));
                if ($existe == "T") {
                    return response()->json(['message' => 'Cliente ya existente.'], 400);
                }
            }
            if ($cliente->save()) {
                return response()->json(['message' => 'Cliente registrado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al registrar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error ' . $error->getMessage()], 500);
        }
    }
    public function update($id, Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtNombre')->message('El nombre del Cliente es obligatorio');
        $validator->labels([
            'nombre' => 'txtnombre'
        ]);
        if (!$validator->validate()) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => $validator->errors()
            ];
            return response()->json($data, 422);
        }
        try {
            $cliente = new Cliente();
            $cliente->txtRUC = $request->get('txtRUC');
            $cliente->txtDNI = $request->get('txtDNI');
            $cliente->txtNombre = $request->get('txtNombre');
            $cliente->txtDireccion = $request->get('txtDireccion');
            $cliente->txtCiudad = $request->get('txtCiudad');
            $cliente->txtUbigeo = $request->get('cmbUbigeo');
            // if (!empty($request->get('txtRUC'))) {
            //     $existe = $cliente->consultarclientexruc($request->get('txtRUC'));
            //     if ($existe == "T") {
            //         return response()->json(['message' => 'Cliente ya existente.'], 400);
            //     }
            // }
            // if (!empty($request->get('txtDNI'))) {
            //     $existe = $cliente->consultarclientexdni($request->get('txtDNI'));
            //     if ($existe == "T") {
            //         return response()->json(['message' => 'Cliente ya existente.'], 400);
            //     }
            // }
            // if (!empty($request->get('txtNombre'))) {
            //     $existe = $cliente->consultarclientexrazon($request->get('txtNombre'));
            //     if ($existe == "T") {
            //         return response()->json(['message' => 'Cliente ya existente.'], 400);
            //     }
            // }
            // $idCliente = $cliente->buscarid($id);
            if ($cliente->update($id)) {
                return response()->json(['message' => 'Actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error ' . $error->getMessage()], 500);
        }
    }
    public function darBaja($id)
    {
        try {
            if ($this->cliente->darBaja($id)) {
                return response()->json(['message' => 'Eliminado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al eliminar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error ' . $error->getMessage()], 500);
        }
    }
}
