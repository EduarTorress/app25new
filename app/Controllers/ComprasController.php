<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Models\Compra;
use App\Models\NotasCredito;
use App\Models\OCompras;
use App\Models\Ventas;
use App\Services\CarritoService;
use Core\Routing\Controller;
use Valitron\Validator;
use ZipArchive;

class ComprasController extends Controller
{
    function index()
    {
        // $dctos = new DocumentoController();
        // $listadctos = $dctos->Obtenerdctosocompras($cbuscar = "");
        // return \view('compras/co_compras', ['titulo' => 'Registrar Otras Compras', 'lista' => $listadctos]);
    }
    function indexcompra()
    {
        // $datosproveedorp = session()->get('proveedor', []);
        $idcompra = \session()->get('idcompra', 0);
        if ($idcompra > 0) {
            $ctitulo = 'Act. Compra';
        } else {
            $ctitulo = 'Regs. Compra';
        }
        $serie = \session()->get('cndoc', '');
        $num = \session()->get('num', '');

        $datosproveedor = array(
            'idprov' => \session()->get('idprov', 0),
            'razo' => \session()->get('razo', ''),
            'tdoc' => \session()->get('tdoc', 0),
            'cndoc' => $serie,
            'num' => $num,
            'ndo2' => \session()->get('ndo2', ''),
            'mone' => \session()->get('mone', ''),
            'form' => \session()->get('form', ''),
            'alm' => \session()->get('alm', $_SESSION['idalmacen']),
            'fech' => \session()->get('fechi', ''),
            'fecr' => \session()->get('fechf', ''),
            'optigv' => \session()->get('optigv', 'I')
        );
        $v = "R";
        return \view('compras/index', ['titulo' => $ctitulo, 'datosproveedor' => $datosproveedor, 'serie' => $serie, 'num' => $num, 'idcompra' => $idcompra, 'v' => $v]);
    }
    function grabarSesion(Request $request)
    {
        \session()->set('idprov', $request->get('idprov'));
        \session()->set('razo', $request->get('razo'));
        \session()->set('tdoc', $request->get('tdoc'));
        \session()->set('cndoc', $request->get('cndoc'));
        \session()->set('num', $request->get('num'));
        \session()->set('ndo2', $request->get('ndo2'));
        \session()->set('alm', $request->get('alm'));
        \session()->set('form', $request->get('form'));
        \session()->set('mone', $request->get('mone'));
        \session()->set('fechi', $request->get('fechi'));
        \session()->set('fechf', $request->get('fechf'));
        \session()->set('dolar', $request->get('dolar'));
        \session()->set('optigv', $request->get('optigv'));
    }
    function listardetalle()
    {
        $carritoc = session()->get('carritoc', []);
        $idcompra = \session()->get('idcompra', 0);
        if ($idcompra > 0) {
            $btn = 'Modificar';
        } else {
            $btn = 'Grabar';
        }
        $total = number_format(CarritoService::totalCompra(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
        $checknodescontarstock = \session()->get('checknodescontarstock', 'false');
        $cvista = \retornavista('compras', 'detalle');
        return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items, 'btn' => $btn, 'checknodescontarstock' => $checknodescontarstock]);
    }
    function verificarsiyaesta($idart)
    {
        if (CarritoService::siestacompras($idart)) {
            return true;
        } else {
            return false;
        }
    }
    function agregaritem(Request $request)
    {
        // $idart = $request->get('txtcodigo');
        // if ($this->verificarsiyaesta($idart)) {
        //     $data = [
        //         'message' => 'Producto ya agregado a la compra',
        //         'rpta' => 'N'
        //     ];
        //     return response()->json($data, 422);
        // }
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es obligatoria');
        $validar->rule("numeric", "txtprecio")->message('El Precio debe ser númerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser númerico');
        $validar->rule("min", "txtcantidad", 1)->message('La Cantidad debe de ser mayor a 0');
        $validar->labels([
            'precio' => 'txtprecio',
            'cantidad' => 'txtcantidad'
        ]);
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        $producto = array();
        $producto = array(
            'coda' => $request->get("txtcodigo"),
            'descri' => $request->get("txtdescripcion"),
            'unidad' => $request->get('txtunidad'),
            'cantidad' => $request->get('txtcantidad'),
            'precio' => $request->get("txtprecio"),
            'precio1' => $request->get("precio1"),
            'precio2' => $request->get("precio2"),
            'precio3' => $request->get("precio3"),
            'stock' => $request->get('stock'),
            'costo' => $request->get('costo'),
            'presentaciones' => $request->get('presentaciones'),
            'cantequi' => $request->get('cantequi'),
            'presseleccionada' => $request->get('presseleccionada')
        );

        CarritoService::agregarItemCompra($producto);
        $total = number_format(CarritoService::totalCompra(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);

        $carritoc = session()->get('carritoc', []);
        $cvista = \retornavista('compras', 'detalle');

        // return response()->json([
        //     'message' => 'Item agregado correctamente',
        //     'total' => $total,
        //     'numero_items' => $numero_items,
        //     'carritoc' => session()->get("carritoc", [])
        // ], 200);
        $checknodescontarstock = \session()->get('checknodescontarstock', 'false');

        return view($cvista, [
            'carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items, 'checknodescontarstock' => $checknodescontarstock
        ]);
    }
    function quitaritem(Request $request)
    {
        $pos = $request->get('indice');
        CarritoService::quitarItemCompra($pos);
        $carritoc = session()->get('carritoc', []);
        $total = number_format(CarritoService::totalCompra(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('compras', 'detalle');
        $checknodescontarstock = \session()->get('checknodescontarstock', 'false');
        return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items, 'checknodescontarstock' => $checknodescontarstock]);
    }
    function LimpiarSesion()
    {
        session()->remove('carritoc');
        session()->remove('proveedor');
        session()->remove('idcompra');
        session()->remove('razo');
        session()->remove('tdoc');
        session()->remove('cndoc');
        session()->remove('num');
        session()->remove('ndo2');
        session()->remove('alm');
        session()->remove('form');
        session()->remove('mone');
        session()->remove('fechi');
        session()->remove('fechf');
        session()->remove('dolar');
        session()->remove('optigv');
        session()->remove('checknodescontarstock');
    }
    function limpiar()
    {
        $this->LimpiarSesion();
        $carritoc = session()->get('carritoc', []);
        $total = number_format(CarritoService::totalCompra(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('compras', 'detalle');
        $checknodescontarstock = \session()->get('checknodescontarstock', 'false');
        return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items, 'checknodescontarstock' => $checknodescontarstock]);
    }
    function soloItem(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'unidad' => ($request->get('unidad')),
            'cantidad' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtcantidad')),
            'precio' => floatval(($request->get('txtprecio') <= 0.00) ? 0 : $request->get('txtprecio')),
            'cantequi' => $request->get('cantequi'),
            'lote' => $request->get('lote'),
            'fechavto' => $request->get('fechavto'),
            'presseleccionada' => $request->get('presseleccionada'),
            'activo' => 'A'
        );
        CarritoService::editarProductoCompra($producto);
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function checkafecto(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'checkafecto' => $request->get('marcado'),
        );
        CarritoService::editarProductocheckafecto($producto);
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function checknodescontarstock(Request $request)
    {
        // $producto = array();
        // $producto = array(
        //     'indice' => $request->get('indice'),
        //     'checkafecto' => $request->get('marcado'),
        // );
        // CarritoService::editarProductocheckafecto($producto);
        $_SESSION['checknodescontarstock'] = '' . $request->get('checknodescontarstock');
        return response()->json([
            'message' => 'Check marcado correctamente',
            'array' => []
        ], 200);
    }
    function indexListaCompras()
    {
        return \view('compras/informes/cab_lcompras', ['titulo' => 'Listar Compras']);
    }
    function listarComprasXFecha(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbmoneda = $request->get('cmbmoneda');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $compra = new Compra();
        $lista = $compra->listarComprasxFecha($dfi, $dff, $cmbmoneda, $cmbAlmacen);
        return \view('compras/informes/listacompras', ['listado' => $lista]);
    }
    function indexListaComprasPLE()
    {
        return \view('compras/informes/cab_lcomprasPLE', ['titulo' => 'Registro de compras PLE']);
    }
    function listarComprasXFechaPLE(Request $request)
    {
        // $compra = new Compra();
        $datapost = array('mes' => $request->get('mes'), 'ano' => $request->get('ano'), 'ruc' => $_SESSION['gene_nruc']);
        // $lista = $compra->listarComprasxFechaPLE($request->get('mes'), $request->get('ano'));
        $listado = $this->obtenerlistadople($datapost);
        return \view('compras/informes/listacomprasPLE', ['listado' => $listado]);
    }
    function exportarsire(Request $request)
    {
        $datapost = array('mes' => $request->get('mes'), 'ano' => $request->get('ano'), 'ruc' => $_SESSION['gene_nruc']);
        $listado = $this->obtenerlistadople($datapost);
        $listadonc = $this->obtenerlistadonotascreditople($datapost);

        $sire = "";
        $fechanota = "";
        $ndocnota = "";
        $serienota = "";
        $tiponota = "";
        foreach ($listado as $l) {
            if ($l['tdoc'] == '07' || $l['tdoc'] == '09') {
                foreach ($listadonc as $lnc) {
                    if ($l['ndoc'] == $lnc['ndoc']) {
                        $fechanota = $lnc['fech'];
                        $serienota = substr($lnc['ndoc'], 0, 4);
                        $ndocnota = substr($lnc['ndoc'], 5, 12);
                        $tiponota = $lnc['tdoc'];
                    }
                }
            }

            // <<Trim(rucempresa)>>|<<Trim(Empresa)>>|<<Periodo>>|<<car>>|<<fechae>>|<<Iif(tipocomp='14',fechae,fvto)>>|
            // <<tipocomp>>|<<Serie>>|<<Iif(fdua=0,'',fdua)>>|<<nrocomp>>|<<''>>|<<tipodocp>>|<<nruc>>|<<Alltrim(proveedor)>>|
            // <<Base>>|<<igv>>|<<exporta>>|<<igvex>>|<<inafecta>>|<<igvng>>|<<Exon>>|<<isc>>|<<icbper>>|<<otros>>|<<Total>>|

            // <<Mone>>|<<Iif(Moneda='S','',Tipocambio)>>|<<Iif(fechn=Ctod("01/01/0001"),'',fechn)>>|<<Iif(tipon='00','',tipon)>>|
            // <<Iif(Left(serien,1)='-','',Trim(serien))>>|<<''>>|<<Iif(Left(ndocn,1)='-','',Round(Val(ndocn),0))>>|<<Tipo>>|<<''>>|
            // <<''>>|<<''>>|<<''>>|<<''>>|<<''>>|<<''>>|<<''>>|<<''>>|<<Alltrim(Auto)>>|<<porcigv>>|<<''>>|<<Ccostos>>|<<Trim(ncta)>>|
            // <<Trim(ncta1)>>|

            $fdua = ($l['tdoc'] == 50 ? $request->get('ano') : '0000');

            $tipodoc = empty($l['ruc']) ? "6" : "1";
            $ruc = empty($l['ruc']) ? $l['dni'] : $l['ruc'];
            $porigv = (floatval($_SESSION['gene_igv']) * 100) - 100;
            //SI ES DNI ES 6 Y SI ES RUC ES 1;

            $sire .= trim($_SESSION['gene_nruc']) . "|" . trim($_SESSION['gene_empresa']) . "|" . trim($request->get('namemes')) . "|" . "" . "|" . $l['fech'] . "|" . ($l['tdoc'] == '14' ?  $l['feche'] :  $l['fechvto']) . "|"
                . $l['tdoc'] . "|" . $l['serie'] . "|" . ($fdua == '0000' ? '' : $fdua) . "|" . $l['ndoc'] . "|" . "" . "|"
                . trim($tipodoc) . "|" . trim($ruc) . "|" . trim($l['razo']) . "|" . $l['valor'] . "|" . $l['vigv'] . "|" . "0" . "|"
                . "0" . "|" . $l['inafecto'] . "|" . "0" . "|" . $l['exon'] . "|" . "0" . "|" . "0" . "|" . "0" .  "|" . $l['importe'] . "|"
                . $l['mone'] . "|" . ($l['mone'] == 'S' ? '' : $_SESSION['gene_dola']) . "|" . $fechanota . "|" . $tiponota . "|" . $serienota . "|" . $ndocnota . "|" . "" . "|"
                . "" . "|" . "" . "|" . "" . "|" . "" . "|" . "" . "|" . "" . "|" . "" . "|" . "" . "|" . "" . "|" . $l['auto'] . "|" . $porigv . "|" . "0" . "|" . "" . "|" . "" . "|" . "\n";
        }

        $namefile = 'LE' . $_SESSION['gene_nruc'] . trim($request->get('ano')) . trim($request->get('mes')) . '00080400021112';
        $rutasire = $namefile . ".txt";
        file_put_contents($rutasire, $sire);

        // #2 create zip archive
        $zip = new ZipArchive();
        $zipFile = $namefile . '.zip';
        if ($zip->open($zipFile, ZipArchive::CREATE)) {
            $zip->addFile($rutasire);
        }
        $zip->close();

        // return $sire;
    }
    function obtenerlistadople($datapost)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://companiasysven.com/API/listarcomprasple.php',
            CURLOPT_POSTFIELDS => $datapost,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        return $data['result'];
    }
    function obtenerlistadonotascreditople($datapost)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://companiasysven.com/API/listarcomprasncple.php',
            CURLOPT_POSTFIELDS => $datapost,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        return $data['result'];
    }
    function validaranoactual($txtfechai, $txtfechar)
    {
        $anoactual = date('Y');
        $txtfechai = strtotime($txtfechai);
        $fechai = date('Y', $txtfechai);
        if ($fechai != $anoactual) {
            return 1;
        }
        $txtfechar = strtotime($txtfechar);
        $fechar = date('Y', $txtfechar);
        if ($anoactual != $fechar) {
            return 1;
        }
        return 0;
    }
    function grabar(Request $request)
    {
        $validarano = $this->validaranoactual($request->get('fechi'), $request->get('fechf'));
        if ($validarano == 1) {
            return response()->json(['errors' => 'El año de la fecha de emisión es diferente al actual'], 422);
        }

        $validar = new Validator($request->getBody());
        $validar->rule("required", "tdoc");
        $validar->rule("required", "cndoc");
        $validar->rule("required", "idprov");
        $validar->rule("required", "impo");
        // $validar->rule("required", "coda");
        $validar->rule("required", "form");
        $validar->rule("required", "mon");
        $validar->rule("required", "alm");
        // $validar->rule("required", "ndo2");
        $validar->rule("required", "dolar");
        $validar->rule("required", "igv");

        $cserie = $request->get('cndoc');
        $cserie = $cserie . substr(0, 4);
        switch ($request->get('tdoc')) {
            case '01':
                $validar->rule('regex', $cserie, '/^[F]{1,1}[D|N0-9]{1,1}[0-9]{2,2}$/');
                break;
            case '03':
                $validar->rule('regex', $cserie, '/^[B|B]{1,1}[D|N0-9]{1,1}[0-9]{2,2}$/');
                break;
        }

        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }

        if (empty($_SESSION["carritoc"])) {
            return response()->json(['message' => 'Se requiere productos para registrar la compra'], 422);
        }

        if (empty($_SESSION['checknodescontarstock'])) {
            $_SESSION['checknodescontarstock'] = 'false';
        }

        $compra = new Compra();
        $var =  $request->get('deta');
        $deta = (isset($var)) ? $request->get('deta') : "";

        $cuentasxpagar = json_decode($request->get("cuentasxpagar"));
        $cuentasxpagar = json_decode(json_encode($cuentasxpagar), true);

        $cabecera = array(
            "tdoc" => $request->get("tdoc"),
            "cndoc" => $request->get("cndoc"),
            "form" => $request->get("form"),
            "fechi" => $request->get("fechi"),
            "fechf" => $request->get("fechf"),
            "deta" => $deta,
            "valor" => $request->get("valor"),
            "nigv" => $request->get("nigv"),
            "impo" => $request->get("impo"),
            "ndo2" => $request->get("ndo2"),
            "mon" => $request->get("mon"),
            "dolar" => $request->get("dolar"),
            //IGV 
            //CTG
            "idprov" => $request->get("idprov"),
            'txtproveedor' => $request->get('txtproveedor'),
            //CMVTO
            "nidus" => session()->get('usuario_id'),
            //OPT
            "alm" => $request->get("alm"),
            //N1
            //N2
            //N3
            "nitem" => str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT),
            "igv" => $request->get("igv"),
            'actualizarprecios' => $request->get('actualizarprecios'),
            'pimpo' => $request->get('pimpo'),
            'cmbtipodocumentocuentasxpagar' => $request->get('cmbtipodocumentocuentasxpagar'),
            'cuentasxpagar' => $cuentasxpagar,
            'exonerado' => $request->get('exonerado')
        );
        if ($compra->grabarCompra($cabecera)) {
            $this->LimpiarSesion();
            $carritoc = session()->get('carritoc', []);
            $total = number_format(CarritoService::totalCompra(), 2, '.', '');
            $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
            $checknodescontarstock = \session()->get('checknodescontarstock', 'false');
            $cvista = \retornavista('compras', 'detalle');
            return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items, 'checknodescontarstock' => $checknodescontarstock]);
        } else {
            return response()->json(['message' => 'Error al registrar compra'], 422);
        }
    }
    function buscarCompraPorId($idauto)
    {
        $compra = new Compra();
        $nrocompra = "";
        $this->LimpiarSesion();
        $carritoc = session()->get('carritoc', []);
        $lista = $compra->buscarCompraPorID($idauto);
        // session()->set('idauto', $idauto);
        $i = 0;
        $montototal = 0;
        $subtotal = 0;
        foreach ($lista as $item) {
            if ($i == 0) {
                $datosproveedor = array(
                    'idauto' => $item['idauto'],
                    'alm' => $item['alma'],
                    'fech' => $item['fech'],
                    'fecr' => $item['fecr'],
                    'form' => $item['form'],
                    'tdoc' => $item['tdoc'],
                    'dolar' => $item['dolar'],
                    'tipo' => $item['tipo'],
                    'mone' => $item['mone'],
                    'razo' => $item['razo'],
                    'idprov' => $item['idprov'],
                    'ndo2' => $item['ndo2'],
                    'optigv' => $item['incl'],
                    'pimpo' => $item['pimpo']
                    // 'detalle' => (isset($item['detalle'])) ? $item['detalle'] : '',
                );
                $nrocompra = $item['ndoc'];
                $idauto = $item['idauto'];
                $checknodescontarstock = ($item['alma'] == 0) ? 'true' : 'false';
            }

            $subtotal = $item['prec'] * $item['cant'];
            $montototal = $subtotal + $subtotal;
            $i++;

            $c[] = array(
                'coda' => $item["idart"],
                'descri' => $item["descri"],
                'unidad' => $item['unid'],
                'cantidad' => $item['cant'],
                'precio' => $item["prec"],
                'preciocopia' => $item['prec'],
                'nreg' => $item["idkar"],
                'idprov' => $item['idprov'],
                'subtotal' => $item['prec'] * $item['cant'],
                'activo' => 'A',
                'epta_idep' => empty($item['epta_idep']) ? 0 : $item['epta_idep'],
                'pres_desc' => empty(trim($item['pres_desc'])) ? 'UNID' : $item['pres_desc'],
                'epta_cant' => empty($item['epta_cant']) ? 1 : $item['epta_cant'],
                'epta_prec' => empty($item['epta_prec']) ? $item['prec'] : $item['epta_prec'],
                'presseleccionada' => empty($item['kar_epta']) ? 0 : $item['kar_epta'],
                'kar_equi' => empty($item['kar_equi']) ? 1 : $item['kar_equi'],
                'checkafecto' => (floatval($item['kar_tigv']) > 1 ? "true" : "false"),
                'kar_lote' => empty($item['kar_lote']) ? '' : $item['kar_lote'],
                'kar_fvto' => empty($item['kar_fvto']) ? '' : $item['kar_fvto']
                // ($item['rcom_exon'] > 0 ? true : false)
            );
        }

        $c = empty($c) ? [] : $c;
        if (count($c) < 1) {
            header('Location: /ocompras/buscarcompra/' . $idauto);
            return;
        }

        $ltagrupada = array();
        foreach ($c as $k => $producto) {
            $idart = $producto["nreg"];
            $ltagrupada[$idart][] = $producto;
        }

        foreach ($ltagrupada as $k => $items) {
            $presentaciones = [];
            $j = 0;
            foreach ($items as $p) {
                $presentaciones[$j] = array(
                    'epta_idep' => $p['epta_idep'],
                    'pres_desc' => $p['pres_desc'],
                    'epta_cant' => $p['epta_cant'],
                    'epta_prec' => $p['epta_prec']
                );
                $j += 1;
            }

            $carritoc[] = array(
                'coda' => $items[0]["coda"],
                'descri' => $items[0]["descri"],
                'unidad' => $items[0]['unidad'],
                'cantidad' => $items[0]['cantidad'],
                'caant' => $items[0]['cantidad'],
                'precio' => $items[0]["precio"],
                'preciocopia' => $items[0]['precio'],
                'nreg' => $items[0]["nreg"],
                'idprov' => $items[0]['idprov'],
                'subtotal' => $items[0]['precio'] * $items[0]['cantidad'],
                'activo' => 'A',
                'presentaciones' => json_encode($presentaciones),
                'presseleccionada' => $items[0]['presseleccionada'],
                'cantequi' => $items[0]['kar_equi'],
                'checkafecto' => $items[0]['checkafecto'],
                'lote' => $items[0]['kar_lote'],
                'fechavto' => $items[0]['kar_fvto'],
                'activo' => 'A'
            );
        }

        $items = $i;

        session()->set('proveedor', $datosproveedor);
        session()->set('carritoc', $carritoc);
        $titulo = 'Actualizar Compra' . ' ' . $nrocompra;

        // session()->set('nrocompra', $nrocompra);

        $serie = substr($nrocompra, 0, 4);
        $num = substr($nrocompra, 4);

        session()->set('idcompra', $idauto);

        $cvista = \retornavista('compras', 'index');
        $v = "M";

        \session()->set('idprov', $datosproveedor['idprov']);
        \session()->set('razo',  $datosproveedor['razo']);
        \session()->set('tdoc',  $datosproveedor['tdoc']);
        \session()->set('cndoc',  $serie);
        \session()->set('num', $num);
        \session()->set('ndo2',  $datosproveedor['ndo2']);
        \session()->set('alm',  $datosproveedor['alm']);
        \session()->set('form',  $datosproveedor['form']);
        \session()->set('mone',  $datosproveedor['mone']);
        \session()->set('fechi',  $datosproveedor['fech']);
        \session()->set('fechf',  $datosproveedor['fecr']);
        \session()->set('dolar',  $datosproveedor['dolar']);
        \session()->set('optigv',  $datosproveedor['optigv']);
        \session()->set('checknodescontarstock',  $checknodescontarstock);

        return view($cvista, [
            'titulo' => $titulo, 'datosproveedor' => $datosproveedor, 'idcompra' => $idauto, 'serie' => $serie,
            'num' => $num, 'v' => $v, 'carritoc' => $carritoc, 'items' => count($carritoc), 'total' => $montototal,
            'checknodescontarstock' => $checknodescontarstock
        ]);
    }
    function modificar(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "tdoc");
        $validar->rule("required", "cndoc");
        $validar->rule("required", "idprov");
        $validar->rule("required", "impo");
        // $validar->rule("required", "coda");
        $validar->rule("required", "form");
        $validar->rule("required", "mon");
        $validar->rule("required", "alm");
        // $validar->rule("required", "ndo2");
        $validar->rule("required", "dolar");
        $validar->rule("required", "igv");

        $cserie = $request->get('cndoc');
        $cserie = $cserie . substr(0, 4);
        switch ($request->get('tdoc')) {
            case '01':
                $validar->rule('regex', $cserie, '/^[F]{1,1}[D|N0-9]{1,1}[0-9]{2,2}$/');
                break;
            case '03':
                $validar->rule('regex', $cserie, '/^[A-Z]{1,1}[D|N0-9]{1,1}[0-9]{2,2}$/');
                break;
        }

        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }

        if (empty($_SESSION["carritoc"])) {
            return response()->json(['message' => 'Se requiere productos para registrar la compra'], 422);
        }

        if (empty($_SESSION['checknodescontarstock'])) {
            $_SESSION['checknodescontarstock'] = 'false';
        }

        $compra = new Compra();
        $var =  $request->get('deta');
        $deta = (isset($var)) ? $request->get('deta') : "";
        $cabecera = array(
            "tdoc" => $request->get("tdoc"),
            "cndoc" => $request->get("cndoc"),
            "form" => $request->get("form"),
            "fechi" => $request->get("fechi"),
            "fechf" => $request->get("fechf"),
            "deta" => $deta,
            "valor" => $request->get("valor"),
            "nigv" => $request->get("nigv"),
            "impo" => $request->get("impo"),
            "ndo2" => $request->get("ndo2"),
            "mon" => $request->get("mon"),
            "dolar" => $request->get("dolar"),
            //IGV 
            //CTG
            "idprov" => $request->get("idprov"),
            'txtproveedor' => $request->get('txtproveedor'),
            //CMVTO
            "nidus" => session()->get('usuario_id'),
            //OPT
            "alm" => $request->get("alm"),
            //N1
            //N2
            //N3
            "nitems" => str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT),
            "igv" => $request->get("igv"),
            'pimpo' => $request->get('pimpo'),
            "nidauto" => \session()->get('idcompra'),
            'actualizarprecios' => $request->get('actualizarprecios'),
            'exonerado' => $request->get('exonerado')
        );
        if ($compra->actualizarCompra($cabecera)) {
            $inventariocontroller = new InventarioController();
            $inventariocontroller->calcularstock();
            $this->LimpiarSesion();
            $carritoc = session()->get('carritoc', []);
            $total = number_format(CarritoService::totalCompra(), 2, '.', '');
            $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
            $cvista = \retornavista('compras', 'detalle');
            $checknodescontarstock = \session()->get('checknodescontarstock', 'false');
            return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items, 'checknodescontarstock' => $checknodescontarstock]);
        } else {
            return response()->json(['message' => 'Error al modificar compra'], 422);
        }
    }
    function indexcompradproducto()
    {
        return \view('compras/informes/indexlistacdp', ['titulo' => 'Rotación de Productos - Compras']);
    }
    function listarcompradproducto(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $compra = new Compra();
        $lista = $compra->listarComprasxProducto($dfi, $dff);
        return \view('compras/informes/re_lcomprasdproducto', ['listado' => $lista]);
    }
    function indexocompra()
    {
        $titulo = 'Registrar Otras Compras';
        $dctos = new DocumentoController();
        $listadctos = $dctos->Obtenerdctosocompras($cbuscar = "");
        return view('ocompras/index', ['titulo' => $titulo, 'listadctos' => $listadctos]);
    }
    function registrarocompra(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idprov");
        $validar->rule("required", "cndoc1");
        $validar->rule("required", "cndoc2");

        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }

        $cuentasxpagar = json_decode($request->get("cuentasxpagar"));
        $cuentasxpagar = json_decode(json_encode($cuentasxpagar), true);
        $datosregistro = [
            'idprov' => $request->get('idprov'),
            'cmbtdoc' => $request->get('cmbtdoc'),
            'cndoc1' => $request->get('cndoc1'),
            'cndoc2' => $request->get('cndoc2'),
            'txtfechai' => $request->get('txtfechai'),
            'txtfechar' => $request->get('txtfechar'),
            'txtfechavto' => $request->get('txtfechavto'),
            'cmbformapago' => $request->get('cmbformapago'),
            'txttipocambio' => $request->get('txttipocambio'),
            'moneda' => $request->get('moneda'),
            'tipogasto' => $request->get('tipogasto'),
            'nt1' => $request->get('nt1'),
            'nt2' => $request->get('nt2'),
            'nt3' => $request->get('nt3'),
            'nt4' => $request->get('nt4'),
            'nt5' => $request->get('nt5'),
            'nt6' => $request->get('nt6'),
            'nt7' => $request->get('nt7'),
            'nt8' => $request->get('nt8'),
            'nidcta1' => $request->get('nidcta1'),
            'nidcta2' => $request->get('nidcta2'),
            'nidcta3' => $request->get('nidcta3'),
            'nidcta4' => $request->get('nidcta4'),
            'nidcta5' => $request->get('nidcta5'),
            'nidcta6' => $request->get('nidcta6'),
            'nidcta7' => $request->get('nidcta7'),
            'nidcta8' => $request->get('nidcta8'),
            'ct1' => $request->get('ct1'),
            'ct2' => $request->get('ct2'),
            'ct3' => $request->get('ct3'),
            'ct4' => $request->get('ct4'),
            'ct5' => $request->get('ct5'),
            'ct6' => $request->get('ct6'),
            'ct7' => $request->get('ct7'),
            'ct8' => $request->get('ct8'),
            'txtreferencia' => $request->get('txtreferencia'),
            'cuentasxpagar' => $request->get('cuentasxpagar'),
            'cmbtipodocumentocuentasxpagar' => $request->get('cmbtipodocumentocuentasxpagar'),
            'cuentasxpagar' => $cuentasxpagar
        ];
        $oCompra = new OCompras();
        if ($oCompra->registrar($datosregistro)) {
            return response()->json(['message' => 'Se registro correctamente', 'ndoc' => $request->get('cndoc1') . $request->get('cndoc2')], 200);
        } else {
            return response()->json(['message' => 'No se generó satisfactoriamente', 'ndoc' => ''], 422);
        }
    }
    function buscarOCompraPorID($idauto)
    {
        $ocompras = new OCompras();

        $datos = $ocompras->buscarxid($idauto);
        $titulo = 'Actualizar ' . $datos[0]['ndoc'];

        $serie = substr($datos[0]['ndoc'], 0, 4);
        $num = substr($datos[0]['ndoc'], 4, 12);

        $dctos = new DocumentoController();
        $listadctos = $dctos->Obtenerdctosocompras($cbuscar = "");

        $cvista = \retornavista('ocompras', 'index');

        return view($cvista, [
            'titulo' => $titulo,
            'idautocompra' => $idauto,
            'serie' => $serie,
            'num' => $num,
            'datos' => $datos,
            'listadctos' => $listadctos
        ]);
    }
    function indexlistacomprasxprov()
    {
        return \view('compras/informes/indexlistacomprasxprov', ['titulo' => 'Informes de Compras x Proveedor']);
    }
    function listacomprasxprov(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $txtidproveedor = $request->get('txtidproveedor');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $compra = new Compra();
        $lista = $compra->listarcomprasxproveedor($dfi, $dff, $txtidproveedor, $cmbAlmacen);
        $comprasbyNdoc = array();
        $ndoc = '';
        $i = 0;
        foreach ($lista as $l) {
            if ($i == 0) {
                $ndoc = $l['ndoc'];
                $comprasbyNdoc[$ndoc][$i] = $l;
                $i = $i + 1;
            } else {
                if ($ndoc == $l['ndoc']) {
                    $comprasbyNdoc[$ndoc][$i] = $l;
                    $i = $i + 1;
                } else {
                    $i = 0;
                    $ndoc = $l['ndoc'];
                    $comprasbyNdoc[$ndoc][$i] = $l;
                    $i = 1;
                }
            }
        }
        return \view('compras/informes/listacomprasxprov', ['listado' => $comprasbyNdoc]);
    }
    function modificarocompra(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idprov");
        $validar->rule("required", "cndoc1");
        $validar->rule("required", "cndoc2");

        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }

        $datosregistro = [
            'idautocompra' => $request->get('idautocompra'),
            'idprov' => $request->get('idprov'),
            'cmbtdoc' => $request->get('cmbtdoc'),
            'cndoc1' => $request->get('cndoc1'),
            'cndoc2' => $request->get('cndoc2'),
            'txtfechai' => $request->get('txtfechai'),
            'txtfechar' => $request->get('txtfechar'),
            'txtfechavto' => $request->get('txtfechavto'),
            'cmbformapago' => $request->get('cmbformapago'),
            'txttipocambio' => $request->get('txttipocambio'),
            'moneda' => $request->get('moneda'),
            'tipogasto' => $request->get('tipogasto'),
            'nt1' => $request->get('nt1'),
            'nt2' => $request->get('nt2'),
            'nt3' => $request->get('nt3'),
            'nt4' => $request->get('nt4'),
            'nt5' => $request->get('nt5'),
            'nt6' => $request->get('nt6'),
            'nt7' => $request->get('nt7'),
            'nt8' => $request->get('nt8'),
            'nidcta1' => $request->get('nidcta1'),
            'nidcta2' => $request->get('nidcta2'),
            'nidcta3' => $request->get('nidcta3'),
            'nidcta4' => $request->get('nidcta4'),
            'nidcta5' => $request->get('nidcta5'),
            'nidcta6' => $request->get('nidcta6'),
            'nidcta7' => $request->get('nidcta7'),
            'nidcta8' => $request->get('nidcta8'),
            'idv1' => $request->get('idv1'),
            'idv2' => $request->get('idv2'),
            'idv3' => $request->get('idv3'),
            'idv4' => $request->get('idv4'),
            'idv5' => $request->get('idv5'),
            'idv6' => $request->get('idv6'),
            'idv7' => $request->get('idv7'),
            'idv8' => $request->get('idv8'),
            'ct1' => $request->get('ct1'),
            'ct2' => $request->get('ct2'),
            'ct3' => $request->get('ct3'),
            'ct4' => $request->get('ct4'),
            'ct5' => $request->get('ct5'),
            'ct6' => $request->get('ct6'),
            'ct7' => $request->get('ct7'),
            'ct8' => $request->get('ct8'),
            'txtreferencia' => $request->get('txtreferencia')
        ];
        $oCompra = new OCompras();
        if ($oCompra->modificar($datosregistro)) {
            return response()->json(['message' => 'Se modificó correctamente', 'ndoc' => $request->get('cndoc1') . $request->get('cndoc2')], 200);
        } else {
            return response()->json(['message' => 'No se modificó satisfactoriamente', 'ndoc' => ''], 422);
        }
    }
    function generardescuento(Request $request)
    {
        $txtdescuento = $request->get('txtdescuento');
        CarritoService::generardescuento($txtdescuento);
        $_SESSION['txtdescuento'] = $txtdescuento;
        $carritoc = session()->get('carritoc', []);
        $total = number_format(CarritoService::totalCompra(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('compras', 'detalle');
        $checknodescontarstock = \session()->get('checknodescontarstock', 'false');
        return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items, 'checknodescontarstock' => $checknodescontarstock]);
    }
    function indexnotascredito()
    {
        $ctitulo = "Nota de Crédito x Compra";
        return view('notascreditocompra/index', ['titulo' => $ctitulo]);
    }
    function listarcomprastonota(Request $request)
    {
        $idproveedor = $request->get('idproveedor');
        $compras = new Compra();
        $listado = $compras->consultarcomprasporproveedor($idproveedor);
        return view('notascreditocompra/tm_listacompras', [
            "listado" => $listado
        ]);
    }
    function listardetallenota(Request $request)
    {
        $idauto = $request->get('idauto');
        $ventas = new Ventas();
        $listado = $ventas->consultarDetalleVtaDirecta($idauto);
        return view('notascreditocompra/detalle', [
            "listado" => $listado
        ]);
    }
    function registrarnotacredito(Request $request)
    {
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => "Sesión vacía"];
            return response()->json($data, 422);
        }
        $nc = new NotasCredito();
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $nc->cndoc = $request->get('txtndocnotacredito');
        $nc->ctdoc = "07";
        $nc->cform = $request->get("form");
        $nc->dfecha = $request->get("fech");
        $nc->cdetalle = $request->get("cmbMotivo");
        $nc->nvalor = $request->get("subtotal");
        $nc->nigv = $request->get("igv");
        $nc->nt = $request->get("total");
        $nc->cndo2 = $request->get("cndo2v");
        $nc->prov = $request->get("razo");
        $nc->nidprov = $request->get("idprov");
        $nc->nitems = count($detalle);


        $nc->ntotal = $request->get("total");
        $nc->nidauto = $request->get("idauto");
        $nc->dfechavv = $request->get("fechvv");
        $nc->nalma = $_SESSION['idalmacen'];
        $nc->nidcodt = $_SESSION['idalmacen'];

        $rpta = $nc->registrarncporcompra($detalle);
        if ($rpta['estado'] == "1") {
            return response()->json(['message' => $rpta['mensaje'], 'ndoc' => $rpta['ndoc']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje']], 422);
        }
    }
}
