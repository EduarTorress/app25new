<?php

namespace App\Controllers;

use App\Models\Bancos;
use App\Models\Caja;
use App\Models\NumerosCuenta;
use App\Models\PlanesContables;
use App\Models\Usuario;
use Core\Clases\Imprimir;
use Core\Http\Request;
use Core\Routing\Controller;
use Illuminate\Support\Facades\Date;

class CajaController extends Controller
{
    function index()
    {
        return \view('liquidaciones/index', ['titulo' => 'Liquidar Caja']);
    }
    function buscar(Request $request)
    {
        $fech = $request->get('txtfech');
        $nidusua = $request->get('cmbusuarios');
        $codt = $_SESSION['almacen'];
        $caja = new Caja();
        if (!empty($_SESSION['config']['cajaxtienda'])) {
            $data = $caja->buscarmulti($fech, $nidusua, $codt);
        } else {
            $data = $caja->buscar($fech, $nidusua, $codt);
        }
        $lista = $data['lista'];
        $total = 0;
        $liquidez = 0;
        $egresos = 0;
        foreach ($lista as $l) {
            // if ($l['tdoc'] == '03' || $l['tdoc'] == '01' || $l['tdoc'] == '20') {
            if ((floatval($l['egresos']) <= 0) && ($l['tipo'] == 'I')) {
                $total = $total + floatval($l['nimpo']);
            } else {
                $egresos = $egresos + floatval($l['egresos']);
            }
            if (floatval($l['efectivo']) > 0) {
                $liquidez = $liquidez + floatval($l['efectivo']);
            }
            // }
        }
        $totalliquidez = $liquidez - $egresos;
        $listasobrantes = $caja->buscarsobrante($fech, $nidusua, $codt);
        $txtsobrante = empty($listasobrantes['lista'][0]['efectivo']) ? 0 : $listasobrantes['lista'][0]['efectivo'];
        $saldo = $caja->verSaldo('2021-01-01', $fech, $nidusua);
        return \view('liquidaciones/listamovimientos', ['lista' => $lista, 'total' => $total, 'saldo' => $saldo['lista'][0], 'sobrante' => $txtsobrante, 'totalliquidez' => $totalliquidez]);
    }
    function indexIngresosEgresos()
    {
        return \view('liquidaciones/indexIngresosEgresos', ['titulo' => 'Registrar Ingresos - Egresos']);
    }
    function registrarIngresoEgreso(Request $request)
    {
        $caja = new Caja();
        $caja->dfecha = $request->get('dfecha');
        $caja->cndoc = $request->get('cndoc');
        $caja->cdeta = $request->get('cdeta');
        $caja->sdeudor = $request->get('sdeudor');
        $caja->sacreedor = $request->get('sacreedor');
        $caja->cmone = 'S';
        $caja->ndolar = session()->get("gene_dola");
        $caja->nidus = session()->get("usuario_id");
        $caja->nidt = $_SESSION['almacen'];
        $existe = $caja->verificarsiexisteingreso();
        if ($existe == 'T') {
            return response()->json(['message' => 'Ya existe un registro con el mismo número', 'data' => [], 'estado' => '0'], 422);
        }
        $existeapertura = $caja->verificarsiexisteapertura();
        if ($existeapertura == 'T') {
            return response()->json(['message' => 'Ya existe una apertura de Caja', 'data' => [], 'estado' => '0'], 422);
        }
        $rpta = $caja->registrarIngresoEgreso();
        if ($rpta['estado'] == '1') {
            return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data'], 'estado' => $rpta['estado']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data'], 'estado' => '0'], 422);
        }
    }
    function registrarTransferencia(Request $request)
    {
    }
    function enviarresumenxcorreo(Request $request)
    {
        $caja = new Caja();
        $rpta = $caja->buscar($request->get('fecha'), $_SESSION['usuario_id'], $_SESSION['idalmacen']);
        $oimp = new Imprimir();
        $rutapdf = 'liquidacioncaja.pdf';
        $oimp->rucempresa = session()->get('gene_nruc');
        $oimp->cliente = $_SESSION['usuario'];
        $oimp->fecha = $request->get('fecha');
        // $oimp->total = 0.00;
        $oimp->items = $rpta['lista'];
        $oimp->generapdfcaja($rutapdf);
        sleep(2);
        enviarliquidacioncajaxcorreo($request->get('fecha'));
    }
    function indexregistrocajaybancos()
    {
        $cuentasbanco = new NumerosCuenta();
        $listacuentasbanco = $cuentasbanco->listar('%%');
        $planescontables = new PlanesContables();
        $listarplanescontables = $planescontables->listar('10');
        $caja = new Caja();
        $rptampagos = $caja->listarmpagos();
        return \view('cajaybancos/index', [
            'titulo' => 'Registrar Datos a Libro Caja y Bancos',
            'listacuentasbanco' => $listacuentasbanco,
            'listarplanescontables' => $listarplanescontables,
            'listampagos' => $rptampagos['lista']
        ]);
    }
    function listaringresos()
    {
        $caja = new Caja();
        $data = $caja->listaringresos();
        return \view('components/listaingresosxcancelar', [
            'ingresosxcancelar' => $data['lista']
        ]);
    }
    function listaregresos()
    {
        $caja = new Caja();
        $data = $caja->listaregresos();
        return \view('components/listaegresosxcancelar', [
            'egresosxcancelar' => $data['lista']
        ]);
    }
    function generarticketcaja(Request $request)
    {
        $caja = new Caja();
        $dataaperturacaja = $caja->buscarapertura(date('Y-m-d', strtotime($request->get('fecha'))), $request->get('nidusua'));
        $oimp = new Imprimir();
        $fecha = $request->get('fecha');
        $time = strtotime($fecha);
        $newformatfech = date('Y-m-d', $time);
        $oimp->fecha = ($newformatfech);
        $oimp->usuario = ($request->get('usuario'));
        $oimp->efectivo = Round(floatval($request->get('efectivo')), 2);
        $oimp->yape = ($request->get('yape'));
        $oimp->plin = ($request->get('plin'));
        $oimp->tarjeta = ($request->get('tarjeta'));
        $oimp->deposito = ($request->get('deposito'));
        $oimp->credito = ($request->get('credito'));
        $oimp->referencia = $request->get('txtreferencia');
        $oimp->sobrante = $request->get('sobrante');
        $oimp->egresos = $request->get('egresos');
        $oimp->apertura = empty($dataaperturacaja['lista'][0]['lcaj_deud']) ? 0 : $dataaperturacaja['lista'][0]['lcaj_deud'];
        $efectivoconegreso = floatval($request->get('efectivo')) - floatval($request->get('egresos'));
        $oimp->total =  $efectivoconegreso + floatval($request->get('yape')) + floatval($request->get('plin')) + floatval($request->get('tarjeta')) + floatval($request->get('deposito')) + floatval($request->get('credito'));
        $rutapdf = 'ticketcaja.pdf';
        $oimp->generarticketcaja($rutapdf, 'I');
    }
    function registraringresolibro(Request $request)
    {
        // data.append("cmbnrocuentas", $("#cmbnrocuentas").val());
        // data.append("cmbctas", $("#cmbctas").val());
        // data.append("cmbintereses", $("#cmbintereses").val());
        // data.append("txtintereses", $("#txtintereses").val());
        // data.append("cmbcomisiones", $("#cmbcomisiones").val());
        // data.append("txtcomisiones", $("#txtcomisiones").val());
        // data.append("txtnrooperacion", $("#txtnrooperacion").val());
        // data.append("txttipocambio", $("#txttipocambio").val());
        // data.append("txtinteres", $("#txtinteres").val());
        // data.append("txtcomision", $("#txtcomision").val());
        // data.append("txttotal", $("#txttotal").val());
        // data.append("cuentasxpagar", JSON.stringify(detalle));
        $cabecera = [
            'cmbmediopago' => $request->get('cmbmediopago'),
            'txtfechai' => $request->get('txtfechai'),
            'cmbnrocuentas' => $request->get('cmbnrocuentas'),
            'cmbctas' => $request->get('cmbctas'),
            'cmbintereses' => $request->get('cmbintereses'),
            'txtintereses' => $request->get('txtintereses'),
            'cmbcomisiones' => $request->get('cmbcomisiones'),
            'txtcomisiones' => $request->get('txtcomisiones'),
            'txtnrooperacion' => $request->get('txtnrooperacion'),
            'txttipocambio' => $request->get('txttipocambio'),
            'txtinteres' => $request->get('txtinteres'),
            'txtcomision' => $request->get('txtcomision'),
            'txttotal' => $request->get('txttotal'),
            'txtreferencia' => $request->get('txtreferencia')
        ];
        $detalle = json_decode($request->get("cuentasxpagar"));
        $detalle = json_decode(json_encode($detalle), true);
        $caja = new Caja();
        $rptacaja = $caja->grabaringresolibro($cabecera, $detalle);
        if ($rptacaja['estado'] == '1') {
            return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
        } else {
            return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
        }
    }
    function registraregresolibro(Request $request)
    {
        // data.append("cmbnrocuentas", $("#cmbnrocuentas").val());
        // data.append("cmbctas", $("#cmbctas").val());
        // data.append("cmbintereses", $("#cmbintereses").val());
        // data.append("txtintereses", $("#txtintereses").val());
        // data.append("cmbcomisiones", $("#cmbcomisiones").val());
        // data.append("txtcomisiones", $("#txtcomisiones").val());
        // data.append("txtnrooperacion", $("#txtnrooperacion").val());
        // data.append("txttipocambio", $("#txttipocambio").val());
        // data.append("txtinteres", $("#txtinteres").val());
        // data.append("txtcomision", $("#txtcomision").val());
        // data.append("txttotal", $("#txttotal").val());
        // data.append("cuentasxpagar", JSON.stringify(detalle));
        $cabecera = [
            'cmbmediopago' => $request->get('cmbmediopago'),
            'txtfechai' => $request->get('txtfechai'),
            'cmbnrocuentas' => $request->get('cmbnrocuentas'),
            'cmbctas' => $request->get('cmbctas'),
            'cmbintereses' => $request->get('cmbintereses'),
            'txtintereses' => $request->get('txtintereses'),
            'cmbcomisiones' => $request->get('cmbcomisiones'),
            'txtcomisiones' => $request->get('txtcomisiones'),
            'txtnrooperacion' => $request->get('txtnrooperacion'),
            'txttipocambio' => $request->get('txttipocambio'),
            'txtinteres' => $request->get('txtinteres'),
            'txtcomision' => $request->get('txtcomision'),
            'txttotal' => $request->get('txttotal'),
            'txtreferencia' => $request->get('txtreferencia')
        ];
        $detalle = json_decode($request->get("cuentasxpagar"));
        $detalle = json_decode(json_encode($detalle), true);
        $caja = new Caja();
        $rptacaja = $caja->grabaregresolibro($cabecera, $detalle);
        if ($rptacaja['estado'] == '1') {
            return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
        } else {
            return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
        }
    }
    function indexregistrocajayefectivo()
    {
        return \view('cajayefectivo/index', [
            'titulo' => 'Registrar Datos a Libro Caja Efectivo'
        ]);
    }
    function registraringresolibroefectivo(Request $request)
    {
        $cabecera = [
            'txtfechai' => $request->get('txtfechai'),
            'cmbcuentas' => $request->get('cmbcuentas'),
            'txtcuentas' => $request->get('txtcuentas'),
            'cmbmoneda' => $request->get('cmbmoneda'),
            'txtvalor' => $request->get('txtvalor'),
            'txttotal' => $request->get('txttotal'),
            'txtreferencia' => $request->get('txtreferencia'),
            'txttipocambio' => $request->get('txttipocambio')
        ];
        $caja = new Caja();
        $rptacaja = $caja->registraringresolibroefectivo($cabecera);
        if ($rptacaja['estado'] == '1') {
            return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
        } else {
            return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
        }
    }
    function registraregresolibroefectivo(Request $request)
    {
        $cabecera = [
            'txtfechai' => $request->get('txtfechai'),
            'cmbcuentas' => $request->get('cmbcuentas'),
            'txtcuentas' => $request->get('txtcuentas'),
            'cmbmoneda' => $request->get('cmbmoneda'),
            'txtvalor' => $request->get('txtvalor'),
            'txttotal' => $request->get('txttotal'),
            'txtreferencia' => $request->get('txtreferencia'),
            'txttipocambio' => $request->get('txttipocambio')
        ];
        $caja = new Caja();
        $rptacaja = $caja->registraregresolibroefectivo($cabecera);
        if ($rptacaja['estado'] == '1') {
            return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
        } else {
            return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
        }
    }
    function cambiarfecha(Request $request)
    {
        $usua = $request->get("txtUsuario");
        $pass = $request->get("txtPassword");
        $ousuario = new Usuario();
        $valor = $ousuario->verificarusuarioadministador(trim($usua), $pass);
        if (!empty($valor[0]['idusua'])) {
            return response()->json(['message' => 'ok', 'estado' => '1'], 200);
        } else {
            return response()->json(['message' => 'Las credenciales no son correctas', 'estado' => '0'], 422);
        }
    }
    function indexlistarcajabanco()
    {
        $nc = new NumerosCuenta();
        $listabancos = $nc->listar('%%');
        return \view('cajaybancos/informes/indexlistar', [
            'titulo' => 'Reporte de Libro Caja y Bancos',
            'listabancos' => $listabancos
        ]);
    }
    function listarinformescajaybancos(Request $request)
    {
        $txtfechai = $request->get('txtfechai');
        $txtfechaf = $request->get('txtfechaf');
        $banco = $request->get('cmbbancos');
        $caja = new Caja();
        $data = $caja->listarinformescajaybancos($txtfechai, $txtfechaf, $banco);
        $listarsaldoinicial = $caja->listarsaldoinicial($txtfechai, $banco);
        return \view('cajaybancos/informes/listarinformescajaybancos', [
            'listado' => $data['lista'],
            'listarsaldoinicial' => $listarsaldoinicial['lista']
        ]);
    }
}
