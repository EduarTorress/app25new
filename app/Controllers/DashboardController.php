<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Dashboard;
use App\Models\DatosGlobales;
use Core\Routing\Controller;
use App\Models\ValorIGV;
use Core\Http\Request;
use Valitron\Validator;

class DashboardController extends Controller
{
    public function __construct()
    {
        $middleware = new AuthAdminMiddleware(['inicio']);
        $this->registerMiddleware($middleware);
    }
    public function inicio()
    {
        return view('admin/info');
    }
    public function index()
    {
        $igv = new ValorIGV();
        $igv->obtenerIGV();
        $info = new DatosGlobales();
        $info->informacion();
        if (empty(session()->get('usuario_id'))) {
            return view('auth/login');
        }
        return view('admin/dashboard');
    }
    public function obtenerDatos(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "alm")->message('Almacen es Obligatorio');
        $validar->rule("required", "serie")->message('Serie es Obligatorio');
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        \session()->set('alm', $request->get('alm'));
        \session()->set('serie', $request->get('serie'));
        // return view('admin/dashboard');
        return response()->json([
            'message' => 'Se grabo correctamente'
        ], 200);
    }
    public function obtenerpanel()
    {
        $dashboard = new Dashboard();
        $totalproductos = $dashboard->totalproductos();
        $totalclientes = $dashboard->totalclientes();
        $totalventas = $dashboard->totalventas();
        $montoventassoles = $dashboard->montoventassoles();
        $montoventasdolares = $dashboard->montoventasdolares();
        $totalpedidos = $dashboard->totalpedidos();
        $totalventasporano = $dashboard->totalventasporano();
        $totalpedidosporano = $dashboard->totalpedidosporano();
        $totalmontoventas = $dashboard->totalmontoventas();
        return view('layouts/panel', [
            'totalproductos' => $totalproductos,
            'totalclientes' => $totalclientes,
            'totalventas' => $totalventas,
            'montoventassoles' => $montoventassoles,
            'montoventasdolares' => $montoventasdolares,
            'totalpedidos' => $totalpedidos,
            'totalventasporano' => $totalventasporano,
            'totalpedidosporano' => $totalpedidosporano,
            'totalmontoventas' => $totalmontoventas
        ]);
    }
    function calcularfechavto(Request $request)
    {
        $fecha = $request->get('txtfecha');
        if (empty($request->get('txtdias'))) {
            $dias = ' + ' . '0' . ' days';
        } else {
            $dias = ' + ' . $request->get('txtdias') . ' days';
        }
        $fechavto = strtotime($fecha . $dias);
        return date('Y-m-d', $fechavto);
    }
}
