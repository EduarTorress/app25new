<?php

namespace App\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Sucursal;
use Core\Http\Request;
use Core\Routing\Controller;

class InventarioController extends Controller
{
    public function indexkardex()
    {
        $titulo = 'Kardex';
        $tiendas = SucursalController::listarsucursales();
        return view('inventarios/indexkardex', ["titulo" => $titulo, 'tiendas' => $tiendas]);
    }
    function listarkardex(Request $request)
    {
        $obj = new Inventario();
        $obj->ncoda = $request->get("ncoda");
        $obj->dfechaf = $request->get("dff");
        $obj->dfechai = $request->get("dfi");
        $obj->ntienda = $request->get("ntienda");
        $sucursal = new Sucursal();
        $lista = $obj->listarkardex();

        $calma = 0;
        $x = 0;
        $sw = "N";
        $ing = 0;
        $item = array();
        $egr = 0;
        $cm = "";
        foreach ($lista as $l) {
            if ($l['fech'] < $obj->dfechai) {
                if ($l['tipo'] == "C") {
                    $calma += $l['cant'];
                } else {
                    $calma -= $l['cant'];
                }
            } else {
                if ($x == 0) {
                    $item[] = array(
                        "fecha" => $l['fech'],
                        "tdoc" => "",
                        "dcto" => "",
                        "razo" => "Stock Inicial",
                        "ingr" => 0,
                        "egre" => 0,
                        "saldo" => $calma,
                        "moneda" => "",
                        "precio" => 0,
                        "usua" => "",
                        "fusua" => "",
                        "usua1" => "",
                        "tipomvto" => ""
                    );
                }
                $x++;
                $sw = 'S';
                $nprecio = ($l['tipo'] == 'C' ? $l['prec'] * $l['igv'] : $l['prec']);
                if ($l['tipo'] == 'C') {
                    $calma = $calma + $l['cant'];
                    $ing += $l['cant'];
                    if (is_null($l['proveedor'])) {
                        if ($obj->ntienda == $l['ndo2']) {
                            $nh = $l['codt'];
                        } else {
                            $nh = intval($l['ndo2']);
                        }
                        $crazon = "Ingresa Desde " . ($nh > 0 ? $sucursal->nombresucursal($nh) : "");
                    } else {
                        $crazon = $l['proveedor'];
                    }
                    switch ($l['tdoc']) {
                        case '01':
                        case '09':
                            $cm = 'Compras';
                            break;
                        case 'II':
                            $cm = 'Inventarios';
                            break;
                        case 'AJ':
                            $cm = 'Ajustes';
                            break;
                        case 'TT':
                            $cm = 'Transferencias';
                            break;
                        case '99':
                            $cm = 'Reposiciones';
                            break;
                    }
                    $item[] = array(
                        "fecha" => $l['fech'],
                        "tdoc" => $l['tdoc'],
                        "dcto" => $l['ndoc'],
                        "razo" => $crazon,
                        "ingr" => $l['cant'],
                        "egre" => 0,
                        "saldo" => $calma,
                        "moneda" => $l['cmoneda'],
                        "precio" => $nprecio,
                        "usua" => $l['usua'],
                        "fusua" => $l['fusua'],
                        "usua1" => $l['usua1'],
                        "tipomvto" => $cm
                    );
                } else {
                    $calma = $calma - $l['cant'];
                    $egr += $l['cant'];
                    if (is_null($l['cliente'])) {
                        $crazon = 'Salida A ' . (intval($l['ndo2']) > 0 ? $sucursal->nombresucursal($l['ndo2']) : '');
                    } else {
                        $crazon = $l['cliente'];
                    }
                    switch ($l['tdoc']) {
                        case '01':
                        case '03':
                        case '07':
                        case '08':
                        case '20':
                            $cm = 'Ventas';
                            break;
                        case 'TT':
                            $cm = 'Transferencias';
                            break;
                        case '99':
                            $cm = 'Reposiciones';
                            break;
                    }
                    $item[] = array(
                        "fecha" => $l['fech'],
                        "tdoc" => $l['tdoc'],
                        "dcto" => $l['ndoc'],
                        "razo" => $crazon,
                        "ingr" => 0,
                        "egre" => $l['cant'],
                        "saldo" => $calma,
                        "moneda" => $l['cmoneda'],
                        "precio" => $nprecio,
                        "usua" => $l['usua'],
                        "fusua" => $l['fusua'],
                        "usua1" => $l['usua1'],
                        "tipomvto" => $cm
                    );
                }
            }
        }

        if ($sw == 'N') {
            $opro = new Producto();
            $stock = $opro->calcularstockproducto($request->get("ncoda"), $request->get("ntienda"));
            $item[] = array(
                "fecha" => $request->get("dff"),
                "tdoc" => "",
                "dcto" => "",
                "razo" => "STOCK",
                "ingr" => 0,
                "egre" => 0,
                "saldo" => $stock,
                "moneda" => "",
                "precio" => 0,
                "usua" => "",
                "fusua" => "",
                "usua1" => "",
                "tipomvto" => ""
            );
        } else {
            $item[] = array(
                "fecha" => $request->get("dff"),
                "tdoc" => "",
                "dcto" => "",
                "razo" => "TOTALES",
                "ingr" => $ing,
                "egre" => $egr,
                "saldo" => 0,
                "moneda" => "",
                "precio" => 0,
                "usua" => "",
                "fusua" => "",
                "usua1" => "",
                "tipomvto" => ""
            );
        }
        return view('/inventarios/listakardex', ['listado' => $item]);
        // return response()->json($item, 200);
    }
    public function indexexistalmacen()
    {
        $titulo = 'Existencias en Almacen';
        return view('inventarios/indexexistalmacen', ["titulo" => $titulo]);
    }
    public function listarexistenciaalmacen(Request $request)
    {
        $inv = new Inventario();
        $inv->dfechaf = $request->get("txtfecha");
        $lista = $inv->listarexistenciaalmacen();
        $sa_to = 0;
        $cost = 0;
        $nsaldo = 0;
        $saldo = 0;
        $toti = 0;
        $xdebe = 0;
        $xcant = 0;
        $xprec = 0;
        $cost = 0;
        $inventario = [];

        foreach ($lista as $inve) {
            // if ($inve['idart'] == $xcoda) {
            if ($inve['tipo'] == 'V') {
                $saldo = $saldo - $inve['cant'];
                $sa_to = $sa_to - ($cost * $inve['cant']);
            } else {
                $xprec = $inve['precio'];
                if ($xprec == '0') {
                    $xprec = $cost;
                }
                $toti = $toti + ($inve['cant'] == '0' ? 1 : $inve['cant']) * $xprec;
                $xdebe = Round($toti, 2);
                $saldo = $saldo + $inve['cant'];
                if ($saldo < 0) {
                    if ($inve['cant'] <> 0) {
                        $sa_to = Round($saldo * $xprec, 2);
                    } else {
                        $sa_to = $sa_to + $xdebe;
                    }
                } else {
                    if ($sa_to < 0) {
                        $sa_to = Round($saldo * $xprec, 2);
                    } else {
                        if ($sa_to == 0) {
                            $sa_to = Round($saldo * $xprec, 2);
                        } else {
                            $sa_to = Round($sa_to * $xprec, 2);
                        }
                    }
                }
                if ($toti <> 0) {
                    $cost = ($saldo <> 0 ? Round($sa_to / $saldo, 4) : $xprec);
                }
                if ($cost == 0) {
                    $cost = $xprec;
                }
            }
            if ($saldo <> 0) {
                // Insert Into inventario(idart, Descri, Unid, alma, costo)Values(xcoda, cdescri, cUnid, saldo, cost)
                $i = [
                    'idart' => $inve['idart'],
                    'descri' => $inve['descri'],
                    'unid' => $inve['unid'],
                    'stock' => $saldo,
                    'costo' => $cost,
                    'importe' => $saldo * $cost
                ];
                array_push($inventario, $i);
            }
            // }
        }
        return view('/inventarios/listaexisalmacen', ['listado' => $inventario]);
        // echo '<pre>';
        // var_dump($inventario);
        // echo '<pre>';
        // store 0 To sa_to, cost, nsaldo, saldo, toti, xdebe
        // xcoda = inve.idart
        // cdescri = inve.Descri
        // cUnid = inve.Unid
        // Store 0 To xcant, xprec, cost
        // Do While !Eof() And inve.idart = xcoda
        // 	If inve.Tipo = "V"
        // 		saldo = saldo - cant
        // 		sa_to = sa_to - (cost * cant)
        // 	Else
        // 		xprec = Precio
        // 		If xprec = 0  Then
        // 			xprec = cost
        // 		Endif
        // 		toti = toti + (Iif(inve.cant = 0, 1, inve.cant) * xprec)
        // 		xdebe = Round(Iif(inve.cant = 0, 1, inve.cant) * xprec, 2)
        // 		saldo = saldo + cant
        // 		If saldo < 0 Then
        // 			If inve.cant <> 0 Then
        // 				sa_to = Round(saldo * xprec, 2)
        // 			Else
        // 				sa_to = sa_to + xdebe
        // 			Endif
        // 		Else
        // 			If sa_to < 0 Then
        // 				sa_to = Round(saldo * xprec, 2)
        // 			Else
        // 				If sa_to = 0 Then
        // 					sa_to = Round(saldo * xprec, 2)
        // 				Else
        // 					sa_to = Round(sa_to + xdebe, 2)
        // 				Endif
        // 			Endif
        // 		Endif
        // 		If toti <> 0 Then
        // 			cost = Iif(saldo <> 0, Round(sa_to / saldo, 4), xprec)
        // 		Endif
        // 		If cost = 0 Then
        // 			cost = xprec
        // 		Endif
        // 	Endif
        // 	Select inve
        // 	Skip
        // Enddo
    }
    function indexlistaajustes()
    {
        $titulo = 'Ajustes de Inventario';
        return view('inventarios/indexlistaajustes', ["titulo" => $titulo]);
    }
    function listaajustes(Request $request)
    {
        $fechi = $request->get('txtfechai');
        $fechf = $request->get('txtfechaf');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $inv = new Inventario();
        $listado = $inv->listarajustes($fechi, $fechf, $cmbAlmacen);
        return view('inventarios/listaajustes', ["listado" => $listado]);
    }
    function verdetalleajuste(Request $request)
    {
        $idauto = $request->get('idauto');
        $inv = new Inventario();
        $listado = $inv->verdetalleajuste($idauto);
        $data = ['listado' => $listado, 'estado' => $idauto];
        return response()->json($data, 200);
    }
    function calcularstock()
    {
        $inv = new Inventario();
        $inv->calcularstock();
        $data = ['mensaje' => 'Se calculó el Stock correctamente, por favor ingrese nuevamente al sistema', 'estado' => '1'];
        return response()->json($data, 200);
    }
    function indexstockxalmacen()
    {
        $titulo = 'Stock x Almacen';
        return view('inventarios/indexstockxalmacen', ["titulo" => $titulo]);
    }
    function listarstockxalmacen(Request $request)
    {
        $fechf = $request->get('txtfechaf');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $inv = new Inventario();
        $listado = $inv->listarstockxalmacen($fechf, $cmbAlmacen);
        return view('inventarios/listastockxalmacen', ["listado" => $listado,'nalma'=>$cmbAlmacen]);
    }
}
