<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Grupo;
use App\Models\UnidadMedida;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class UnidadesMedidaController extends Controller
{
    private $um;
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->um = new UnidadMedida();
    }
    function index()
    {
        $titulo = 'Unidades de Medida';
        return view('admin/unidadesmedida/index', ['titulo' => $titulo]);
    }
    function lista(Request $request)
    {
        $lista = $this->um->listar($request->get('cbuscar'));
        return view('admin/unidadesmedida/listaunidadesmedida', ['lista' => $lista]);
    }
    static function listargrupos($cbuscar)
    {
        $um = new UnidadMedida();
        $lista = $um->listar($cbuscar);
        return $lista;
    }
    function create()
    {
        $titulo = 'Registrar Unidad de Medida';
        return view('admin/unidadesmedida/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $titulo = 'Editar Unidad de Medida';
        $grupo = $this->um->buscarid($id);
        return view('admin/unidadesmedida/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $grupo, 'id' => $id]);
    }
    function store(Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtnombre')->message('El nombre de Unidad es obligatoria');
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
            $um = new UnidadMedida();
            $um->nombre = $request->get('txtnombre');
            $um->cantidad = $request->get('txtcantidadd');
            $rpta = $um->save();
            if ($rpta['estado'] == '1') {
                return response()->json(['message' => 'Unidad de Medida registrada correctamente', 'id' => $rpta['id']], 200);
            } else {
                return response()->json(['message' => 'Error al registrar Unidad de Medida'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Ocurrió un error ' . $error], 500);
        }
    }
}
