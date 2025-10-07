<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Presentacion;
use App\Models\Unidades;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class PresentacionController extends Controller
{
    private $pres;
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->pres = new Presentacion();
    }
    function listarmodalpres()
    {
        $listarpresentaciones = $this->pres->listarpresentaciones();
        return view('components/modalpresentaciones', [
            'cmbpresentaciones' => $listarpresentaciones
        ]);
    }
    function registrardetapresent(Request $request)
    {
        $idpres = $request->get('idpres');
        $idart = $request->get('idart');
        $prec = $request->get('prec');
        $cant = $request->get('cant');
        $rpta = $this->pres->registrardetapresent($idpres, $idart, $prec, $cant);
        return response()->json(['message' => $rpta['mensaje']], 200);
    }
    function eliminardetapres(Request $request)
    {
        $id = $request->get('id');
        $rpta = $this->pres->eliminardetapres($id);
        return response()->json(['message' => $rpta['mensaje']], 200);
    }
    function listarpresentaciondetalle(Request $request)
    {
        $idart = empty($request->get('idart')) ? '0' : $request->get('idart');
        $listadetapresxproducto = $this->pres->listardetapresxproducto($idart);
        return view('components/listadetapresentaciones', ['listadetapresxproducto' => $listadetapresxproducto]);
    }
}
