<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Routing\Controller;
use App\Models\NotasCredito;

class NotasCreditoController extends Controller
{
    function index()
    {
        $ctitulo = "Nota de Crédito";
        return view('notascredito/index', ['titulo' => $ctitulo]);
    }
    function registrar(Request $request)
    {
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => "Sesión vacía"];
            return response()->json($data, 422);
        }
        $nc = new NotasCredito();
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $nc->ctdoc = "07";
        $nc->cform = $request->get("formv");
        $nc->dfecha = $request->get("fechv");
        $nc->cdetalle = $request->get("cmbMotivo");
        $nc->nvalor = $request->get("subtotal");
        $nc->nigv = $request->get("igv");
        $nc->nt = $request->get("total");
        $nc->cndo2 = $request->get("cndo2v");
        $nc->cliente=$request->get("razov");
        $nc->nidclie = $request->get("idcliev");
        $nc->nitems = count($detalle);

        // $nc->crazo = $request->get("razov");
        $nc->ntotal = $request->get("total");
        $nc->nidauto = $request->get("idauto");
        $nc->dfechavv = $request->get("fechvv");
        $nc->nalma = $_SESSION['idalmacen'];
        $nc->nidcodt = $_SESSION['idalmacen'];

        $rpta = $nc->registrar($detalle);
        if ($rpta['estado'] == "1") {
            return response()->json(['message' => $rpta['mensaje'], 'ndoc' => $rpta['ndoc']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje']], 422);
        }
    }
}