<?php

namespace App\Controllers;

use App\Models\Caja;
use App\Models\CtasporCobrar;
use App\Models\Ventas;
use Core\Http\Request;
use Core\Routing\Controller;

class CobranzasController extends Controller
{
    function index()
    {
        verificaradmin();
        return \view('cobranzas/index', ['titulo' => 'Vencimientos por cliente']);
    }
    function listarvtos(Request $request)
    {
        $ctas = new CtasporCobrar();
        $idcliente = $request->get("idcliente");
        $txtfechai = $request->get("txtfechai");
        $txtfechaf = $request->get("txtfechaf");
        $lista = $ctas->vencimientosporcliente($idcliente,$txtfechai,$txtfechaf);
        return view('cobranzas/listarvtos', ['lista' => $lista]);
    }
    function indexlistacobranzastodo()
    {
         verificaradmin();
        return \view('cobranzas/informes/indexlistarcobranzastodo', ['titulo' => 'Listar Cobranzas']);
    }
    function listarcobranzastodo(Request $request)
    {
        $ctas = new CtasporCobrar();
        $cmbformapago = $request->get("cmbformapago");
        $cmbalmacen = $request->get("cmbalmacen");
        $fecha = $request->get("txtfecha");
        $rpta = $ctas->listarcobranzastodo($cmbformapago, $cmbalmacen, $fecha);
        // return view('cobranzas/informes/listarcobranzastodo', ['listado' => $rpta['lista']]);
        return response()->json(['message' => 'Se logró listar correctamente', 'listado' =>  $rpta['lista']], 200);
    }
    function listarestadocuenta(Request $request)
    {
        $ctas = new CtasporCobrar();
        $idcliente = $request->get("idcliente");
        $cmbalmacen = $request->get("cmbalmacen");
        $cmbmoneda = $request->get("cmbmoneda");
        $lista = $ctas->listarestadocuenta($idcliente, $cmbalmacen, $cmbmoneda);
        return view('cobranzas/listarestadocuenta', ['lista' => $lista['lista']]);
    }
    function registrarcobranzas(Request $request)
    {
        $ctas = new CtasporCobrar();
        $ctas->txtdocumento = $request->get('txtdocumento');
        $ctas->txtfecha = $request->get('txtfecha');
        $ctas->txtimporte = $request->get('txtimporte');
        $ctas->cmbforma = $request->get('cmbforma');
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        $rpta = $ctas->registrarcobranzas($detalle);
        return response()->json(['message' => $rpta['mensaje'], 'listado' => []], 200);
    }
    function consultardetalleventa(Request $request)
    {
        $vtas = new Ventas();
        $idauto = $request->get("idauto");
        $lista = $vtas->consultardetalleventa($idauto);
        return response()->json(['message' => 'Se logró listar correctamente', 'listado' =>  $lista], 200);
    }
    // function index()
    // {
    //     return view('admin/clientes/vtoscliente', ['titulo' => 'Vencimientos por cliente']);
    // }
    // function buscar(Request $request)
    // {
    //     $fech = $request->get('txtfech');
    //     $nidusua = $request->get('cmbusuarios');
    //     $codt = $_SESSION['almacen'];
    //     $caja = new Caja();
    //     $data = $caja->buscar($fech, $nidusua, $codt);
    //     $lista = $data['lista'];
    //     $total = 0;
    //     foreach ($lista as $l) {
    //         if ($l['tdoc'] == '03' || $l['tdoc'] == '01') {
    //             $total = $total + $l['nimpo'];
    //         }
    //     }
    //     $saldo = $caja->verSaldo('2021-01-01', $fech, $nidusua);
    //     return \view('liquidaciones/listamovimientos', ['lista' => $lista, 'total' => $total, 'saldo' => $saldo['lista'][0]]);
    // }
    // function indexIngresosEgresos()
    // {
    //     return \view('liquidaciones/indexIngresosEgresos', ['titulo' => 'Registrar Ingresos - Egresos']);
    // }
    // function registrarIngresoEgreso(Request $request)
    // {
    //     $caja = new Caja();
    //     $caja->dfecha = $request->get('dfecha');
    //     $caja->cndoc = $request->get('cndoc');
    //     $caja->cdeta = $request->get('cdeta');
    //     $caja->sdeudor = $request->get('sdeudor');
    //     $caja->sacreedor = $request->get('sacreedor');
    //     $caja->cmone = 'S';
    //     $caja->ndolar = session()->get("gene_dola");
    //     $caja->nidus = session()->get("usuario_id");
    //     $caja->nidt = $_SESSION['almacen'];
    //     $rpta = $caja->registrarIngresoEgreso();
    //     if ($rpta['estado'] == '1') {
    //         return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 200);
    //     } else {
    //         return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 422);
    //     }
    // }
    // function registrarTransferencia(Request $request)
    // {
    // }
}
