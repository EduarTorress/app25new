<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Routing\Controller;
use App\Models\Dolar;
use App\Models\Empresa;

class ValorDolarController extends Controller
{
    function obtenerDolar(Request $request)
    {
        $fech = $request->get('fech');
        $dolar = new Dolar();
        $valord = $dolar->obtenerDolar($fech, 'C');
        if ($valord == 0) {
            $empresa = new Empresa();
            $valord = $empresa->obtenerdolar($fech);
        }
        $valord = number_format($valord, 3, ".", "");
        return view('components/valordolar', ['dolar' => $valord]);
    }
    function getvaluedolarocompra(Request $request)
    {
        $fech = $request->get('fech');
        $dolar = new Dolar();
        $valord = $dolar->obtenerDolar($fech, 'C');
        if ($valord == 0) {
            $empresa = new Empresa();
            $valord = $empresa->obtenerdolar($fech);
        }
        $valord = number_format($valord, 3, ".", "");
        return response()->json(['valordolar' => $valord], 200);
    }
}
