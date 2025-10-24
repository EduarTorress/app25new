<?php

namespace App\Controllers;

use App\Models\Caja;
use App\Models\Compra;
use App\Models\GuiaRemitente;
use App\Models\NotasCredito;
use App\Models\Producto;
use App\Models\Ventas;
use App\Models\Usuario;
use Core\Routing\Controller;
use Core\Clases\apifacturacion;
use Core\Clases\Cletras;
use Core\Clases\Imprimir;
use Core\Foundation\Application;
use Core\Http\Request;
use ZipArchive;

class CpeController extends Controller
{
    function __construct()
    {
    }
    function consultafne()
    {
        $ctitulo = 'CPE-EMITIDOS POR INFORMAR';
        return view('cpe/re_cpe', ['titulo' => $ctitulo]);
    }
    function noenviados(Request $request)
    {
        $app = Application::getInstance();
        $listado = $app->envio->consultarcpexenviar();
        //header('Content-Type:application/json');
        // return response()->json(['message' => 'Se logró listar correctamente', 'listado' => $listado], 200)
        return view('cpe/re_cpelista', ['listado' => $listado]);
    }
    function informeventas(Request $request)
    {
        return view('cpe/re_vtas');
    }
    function boletaspendientesporenviar(Request $request)
    {
        $empresasel = $request->get('empresa', '');
        setempresa($empresasel);
        $app = Application::getInstance();
        $listado = $app->envio->boletasynotaspendientesporenviar();
        //header('Content-Type:application/json');
        //$data=json_encode(['message' => 'No hay resultados', 'listado' => []]); 
        return view('cpe/re_listabxe', [
            'listado' => $listado
        ]);
    }
    function exportarsire(Request $request)
    {
        $vtascon = new VentasController();
        $datapost = array('mes' => $request->get('mes'), 'ano' => $request->get('ano'), 'ruc' => $_SESSION['gene_nruc']);
        $listado = $vtascon->obtenerlistadople($datapost);
        // $listado = $ventas->registroventasple($request->get('mes'), $request->get('ano'));
        $listadonc = $vtascon->obtenerlistadonotascreditople($datapost);

        // var_dump($listado);
        // <<rucemisor>>|<<Trim(Empresa)>>|<<Periodo>>|<<''>>|<<fech>>|<<fvto>>|<<tipocomp>>|<<Serie>>|<<nrocomp>>|<<''>>|
        // <<Trim(tipodocc)>>|<<Trim(nruc)>>|<<Alltrim(Cliente)>>|<<exporta>>|<<Base>>|<<dsctoigv>>|<<igv>>|<<dsctoigv1>>|
        // <<Exon>>|<<Iif(m.existegratuito='S',tgrati,inafecta)>>|<<isc>>|<<BaseIvap>>|<<ivap>>|<<icbper>>|<<otros>>|<<Total>>|
        // <<Mone>>|<<Iif(Moneda='S','',Tipocambio)>>|<<Iif(fechn=Ctod('01/01/0001'),'',fechn)>>|<<Iif(tipon='00','',tipon)>>|
        // <<Iif(Left(serien,1)='-','',Trim(serien))>>|<<Iif(Left(ndocn,1)='-','',Round(Val(ndocn),0))>>|<<''>>|

        $sire = "";
        $fechanota = "";
        $ndocnota = "";
        $serienota = "";
        $tiponota = "";
        foreach ($listado as $l) {
            if ($l['tdoc'] == '07' || $l['tdoc'] == '09') {
                foreach ($listadonc as $lnc) {
                    if (trim($l['serie'] . $l['ndoc']) == $lnc['ndocc']) {
                        $fechanota = $lnc['fech'];
                        $serienota = substr($lnc['ndoc'], 0, 4);
                        $ndocnota = substr($lnc['ndoc'], 5, 12);
                        $tiponota = $lnc['tdoc'];
                    }
                }
            }
            $sire .= trim($_SESSION['gene_nruc']) . "|" . trim($_SESSION['gene_empresa']) . "|" . trim($request->get('namemes')) . "|" . $l['fech'] . "|" . $l['fvto'] . "|" . $l['tdoc'] . "|" . $l['serie'] . "|" . $l['ndoc'] . "|" . "" . "|"
                . trim($l['tipodoc']) . "|" . trim($l['nruc']) . "|" . trim($l['razo']) . "|" . "0" . "|" . $l['valor'] . "|" . "0" . "|" . $l['vigv'] . "|" . "0" . "|"
                . $l['exon'] . "|" . (isset($l['grati']) ? $l['grati'] : $l['inafecto']) . "|" . "0" . "|" . "0" . "|" . $l['icbper'] . "|" . "0" . "|" . $l['importe'] . "|"
                . $l['mone'] . "|" . ($l['mone'] == 'S' ? '' : $_SESSION['gene_dola']) . "|" . $fechanota . "|" . $tiponota . "|" . $serienota . "|" . $ndocnota . "|" . "" . "|" . "\n";
            $fechanota = "";
            $ndocnota = "";
            $serienota = "";
            $tiponota = "";
        }
        // $st = $app->envio->obtenerxmlycdr($request->get("nidauto"));
        $namefile = 'LE' . $_SESSION['gene_nruc'] . trim($request->get('ano')) . trim($request->get('mes')) . '00080100001011';
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
    function consultabne()
    {
        $ctitulo = 'CPE-BOLETAS POR INFORMAR';
        return view('cpe/re_bxenviar', ['titulo' => $ctitulo]);
    }
    function listat()
    {
        $ctitulo = 'TICKETS POR CONSULTAR';
        return view('cpe/re_tickets', ['titulo' => $ctitulo]);
    }
    function listaticket(Request $request)
    {
        $empresasel = $request->get('empresa', '');
        $df = $request->get("fecha");
        setempresa($empresasel);
        $app = Application::getInstance();
        $listado = $app->envio->Muestratickets($df);
        //header('Content-Type:application/json');
        //$data=json_encode(['message' => 'No hay resultados', 'listado' => []]); 
        return view('cpe/re_listatickets', [
            'listado' => $listado
        ]);
    }
    function consultaticket(Request $request)
    {
        $app = Application::getInstance();
        $empresasel = $request->get('empresa', '');
        setempresa($empresasel);
        $empresa = $app->envio->obteneremisor($request->get("fecha"));
        $ticket = $request->get("ticket");

        $detalle = $app->envio->ObtenerDetalleboletasynotas(
            $request->get("tdoc"),
            $request->get("desde"),
            $request->get("hasta"),
            $request->get("serie")
        );
        $api = new apifacturacion();
        $archivoxml =   pathinfo($request->get("archivo"));
        $nombrexml = $archivoxml['filename'];
        $mensaje = $api->ConsultarTicket($empresa, $ticket, $nombrexml, $detalle);
        if ($mensaje['estado'] == '0') {
            $app->envio->crpta = $mensaje['mensaje'];
            $app->envio->cdr = $mensaje['cdr'];
            $app->envio->GrabaCDRTicket(trim($ticket), $detalle);
        }
        // $oenvio = new envio();
        // $oenvio->crpta = $crpta;
        // $oenvio->cdr = $cdr;
        // $oenvio->GrabaCDRTicket(trim($ticket), $detalle);
        return json_encode($mensaje);
        //ConsultarTicket($emisor, $ticket,$nombrexml,$data,$detalle,$rutaxml="xml/",
        //$rutacdr="cdr/")
    }
    function consultarapi(Request $request)
    {
        header("Content-type:application/json;charset=utf-8");
        $app = Application::getInstance();
        setempresa($request->get("empresa"));
        $detalle = $app->envio->ObtenerDetalleboletasynotas(
            $request->get("tdoc"),
            $request->get("desde"),
            $request->get("hasta"),
            $request->get("serie")
        );
        $i = 1;
        foreach ($detalle as $row) {
            $credenciales = $this->credencialesapisunat($row['nruc']);
            if (empty($credenciales['cliente_id'])) {
                $arraydvto = [
                    "estadocomprobante" => "",
                    "estadoruc" => "7",
                    "condomicilio" => "7",
                    "mensaje" => "Sin Credenciales"
                ];
                echo json_encode($arraydvto);
                $i = 0;
                break;
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-seguridad.sunat.gob.pe/v1/clientesextranet/' . $credenciales['cliente_id'] . '/oauth2/token/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'grant_type=client_credentials&scope=https%3A%2F%2Fapi.sunat.gob.pe%2Fv1%2Fcontribuyente%2Fcontribuyentes&client_id=' . $credenciales['cliente_id'] . '&client_secret=' . $credenciales['secret'],
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $jsonv = json_decode($response, true);
            $clave = 'access_token';
            if (!key_exists($clave, $jsonv)) {
                $arraydvto = [
                    "estadocomprobante" => "",
                    "estadoruc" => "7",
                    "condomicilio" => "7",
                    "mensaje" => "No se Obtuvo el Token para la consulta"
                ];
                echo json_encode($arraydvto);
                $i = 0;
                break;
            }
            $token = $jsonv['access_token'];
            $fields = array("Authorization: Bearer " . $token, "Content-Type: application/json");
            $nidauto = $row['idauto'];
            $dfecha = strtotime(date($row['fech']));
            $a = date("Y", $dfecha);
            $m = date('m', $dfecha);
            $d = date('d', $dfecha);
            $cfecha = $d . '/' . $m . '/' . $a;
            $dctos = json_encode(array(
                "numRuc" => $row['nruc'],
                "codComp" => $row['tdoc'],
                "numeroSerie" => substr($row['ndoc'], 0, 4),
                "numero" => substr($row['ndoc'], -8),
                "fechaEmision" => $cfecha,
                "monto" =>  $row['impo']
            ));
            $curl = curl_init();
            $urlsunat = 'https://api.sunat.gob.pe/v1/contribuyente/contribuyentes/' . $row['nruc'] . '/validarcomprobante';
            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlsunat,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dctos,
                CURLOPT_HTTPHEADER => $fields,
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $rpta = json_decode($response, true);
            $cmensaje = $this->DmensajeApi($rpta);
            $arraydvto = [
                "estadocomprobante" => $rpta["data"]["estadoCp"],
                "estadoruc" => $rpta["data"]["estadoRuc"],
                "condomicilio" => $rpta["data"]["condDomiRuc"],
                "mensaje" => $cmensaje . '-' . $row['ndoc']
            ];
            if (substr($cmensaje, 0, 1) == '0') {
                $app->envio->ActualizaestadoCpe($nidauto, $cmensaje);
            }
            echo json_encode($arraydvto);
        }
        //    echo '<br>'.$request->get("ticket");
        if ($i == 1) {
            $app->envio->ActualizaestadoResumenBoletas($request->get("ticket"), $cmensaje);
        }
    }
    function credencialesapisunat(string $ruc)
    {
        $data = json_decode(file_get_contents($_ENV['DIR_ROOT'] . \DIRECTORY_SEPARATOR . "public" . \DIRECTORY_SEPARATOR . "empresas.json"), true);
        $cliente_id = "";
        $secret = "";
        foreach ($data as $empresa) {
            if ($empresa['ruc'] === $ruc) {
                $cliente_id = $empresa['clienteId'];
                $secret = $empresa['secret'];
                break;
            }
        }
        $credenciales = array(
            "cliente_id" => $cliente_id,
            "secret" => $secret
        );
        return $credenciales;
    }
    function DmensajeApi($rpta)
    {
        $cmensaje = "";
        switch ($rpta["data"]["estadoCp"]) {
            case "0":
                $cmensaje = "Comprobante no informado";
                break;
            case "1":
                $cmensaje = "0 , Comprobante aceptado";
                break;
            case "2":
                $cmensaje = "Comunicado en una baja";
                break;
            case "3":
                $cmensaje = "Con autorización de imprenta";
                break;
            case "4":
                $cmensaje = "No autorizado por imprenta";
                break;
        }
        return $cmensaje;
    }
    function descargarxml(Request $request)
    {
        $app = Application::getInstance();
        setempresa($app->empresa);
        $st = $app->envio->obtenerxmlycdr($request->get("nidauto"));
        $rutaxml = 'descargas/' . $st['nombrexml'];
        // $rutacdr = 'descargas/' . 'R-' . $st['nombrexml'];
        file_put_contents($rutaxml, $st['rcom_xml']);
        // file_put_contents($rutaxml, 'R-' . $st['rcom_xml']);
        return $st['rcom_xml'];
    }
    function descargarpdf(Request $request)
    {
        if (!is_dir('descargas')) {
            \mkdir('descargas', 077, \true);
        }
        $rutapdf = 'descargas/' . $request->get('nombrepdf');

        if ($request->get("tdoc") == '07' or $request->get("tdoc") == '08') {
            $this->imprimirnotascreditoydebito($request->get("nidauto"), $request->get("tipo"), $rutapdf);
        } else {
            $this->imprimirfacturasyboletas($request->get("nidauto"), $request->get("tipo"), $rutapdf);
        }
    }
    function descargarpdfticket(Request $request)
    {
        $rutapdf = 'descargas/' . $request->get('nombrepdf');
        $this->imprimirticket($request->get("nidauto"), $request->get("tipo"), $rutapdf);
    }
    function imprimirnotascreditoydebito($nidauto, $tipo, $rutapdf)
    {
        $onc = new NotasCredito();
        $st = $onc->consultardcto($nidauto, $tipo);
        $oimp = new Imprimir();
        $cletras = new Cletras();
        $i = 1;
        foreach ($st as $fila) {
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $fila['unid'],
                'descri' => $fila['descri'],
                'cant' => number_format($fila['cant'], 2, ".", ","),
                'prec' => number_format($fila['prec'], 2, ".", ","),
                'subtotal' => round($fila['cant'] * $fila['prec'], 2)
            );

            if ($i == 1) {
                $oimp->empresa = $fila['empresa'];
                $oimp->rucempresa = $fila['rucempresa'];
                $oimp->direccionempresa = $fila['ptop'];
                $oimp->tref = $fila['tdoc1'];
                $oimp->referencia = $fila['dcto'];
                $oimp->fechareferencia = date("d/m/Y", strtotime($fila['fech1']));
                switch ($fila['tdoc']) {
                    case '07':
                        $oimp->tipocomprobante = ' NOTA DE CREDITO';
                        break;
                    case '08':
                        $oimp->tipocomprobante = ' NOTA DE DEBITO ';
                        break;
                    default:
                        $oimp->tipocomprobante = ' NOTA DE CREDITO ';
                        break;
                }
                $oimp->numero = $fila['serie'] . '-' . $fila['numero'];
                $oimp->serie = $fila['serie'];
                $oimp->ndoc = $fila['ndoc'];
                $dfecha = date("d/m/Y", strtotime($fila['fech']));

                $oimp->fecha = $dfecha;
                $oimp->fechavto = date("d/m/Y", strtotime($fila['fvto']));
                $datetime1 = date_create($fila['fvto']);
                $datetime2 = date_create($fila['fech']);
                $interval = date_diff($datetime2, $datetime1);
                $oimp->dias = $interval->days;
                $oimp->tdoc = $fila['tdoc'];
                $oimp->ruccliente = $fila['nruc'];
                $oimp->dnicliente = $fila['ndni'];

                $oimp->detraccion = '0';

                $oimp->cliente = ($fila['razo']);
                $oimp->vendedor = "OFICINA";
                $oimp->direccioncliente =  (trim($fila['dire']));
                $oimp->formadepago = ($fila['form'] == 'E' ? 'CONTADO' : 'CREDITO');
                $oimp->moneda = $fila['mone'] === 'S' ? 'SOLES' : 'DOLARES';
                $oimp->importeletras = $cletras->ValorEnLetras($fila['impo'], $fila['mone'] === 'S' ? 'SOLES' : 'DOLARES');
                $oimp->valorgravado = $fila['valor'];
                $oimp->igv = $fila['igv'];
                $oimp->total = $fila['impo'];
                $oimp->vigv = $fila['vigv'];
            }
            $i++;
        }
        $oimp->generapdfnotacreditoydebito($rutapdf);
    }
    function imprimirfacturasyboletas($nidauto, $tipo, $rutapdf)
    {
        $ventas = new Ventas();
        $st = $ventas->consultardcto($nidauto, $tipo);
        $oimp = new Imprimir();
        $cletras = new Cletras();
        $i = 1;
        foreach ($st as $fila) {
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $fila['unid'],
                'descri' => $fila['descri'],
                'cant' => $fila['cant'],
                'prec' => $fila['prec'],
                'subtotal' => round($fila['cant'] * $fila['prec'], 2)
            );

            if ($i == 1) {
                $oimp->empresa = $fila['empresa'];
                $oimp->rucempresa = $fila['rucempresa'];
                $oimp->direccionempresa = $fila['ptop'];
                switch ($fila['tdoc']) {
                    case '01':
                        $oimp->tipocomprobante = ' FACTURA ELECTRONICA';
                        break;
                    case '03':
                        $oimp->tipocomprobante = ' BOLETA DE VENTA ELECTRONICA ';
                        break;
                    case '20':
                        $oimp->tipocomprobante = ' NOTA DE VENTA      ';
                        break;
                    default:
                        $oimp->tipocomprobante = ' NOTA DE CRÉDITO      ';
                        break;
                }
                $oimp->numero = $fila['serie'] . '-' . $fila['numero'];
                $oimp->serie = $fila['serie'];
                $oimp->ndoc = $fila['ndoc'];
                $dfecha = date("d/m/Y", strtotime($fila['dfecha']));
                $oimp->fecha = $dfecha;
                $hora = date("H:i:s", strtotime($fila['fusua']));
                $oimp->hora = $hora;
                $oimp->fechavto = date("d/m/Y", strtotime($fila['fvto']));
                $datetime1 = date_create($fila['fvto']);
                $datetime2 = date_create($fila['dfecha']);
                $interval = date_diff($datetime2, $datetime1);
                $oimp->dias = $interval->days;
                $oimp->tdoc = $fila['tdoc'];
                $oimp->ruccliente = $fila['nruc'];
                $oimp->dnicliente = $fila['ndni'];
                $oimp->guiaremision = $fila['ndo2'];
                $oimp->referencia = isset($fila['deta']) ? $fila['deta'] : '';
                if ($tipo == 'S' or $tipo == 'T') {
                    $oimp->detraccion = $fila['rcom_detr'];
                } else {
                    $oimp->detraccion = '0';
                }
                $oimp->optigv = $fila['incl'];
                $oimp->cliente = ($fila['razo']);
                $oimp->vendedor = $fila['vendedor'];
                $oimp->direccioncliente =  ($fila['direccion']);
                // $oimp->formadepago = ($fila['form'] == 'E' ? 'CONTADO' : 'CREDITO');
                switch ($fila['form']) {
                    case 'E':
                        $oimp->formadepago = "CONTADO";
                        break;
                    case 'C':
                        $oimp->formadepago = "CREDITO";
                        break;
                    case 'D':
                        $oimp->formadepago = "DEPOSITO";
                        break;
                    case 'T':
                        $oimp->formadepago = "TARJETA";
                        break;
                    case 'Y':
                        $oimp->formadepago = "YAPE";
                        break;
                    case 'P':
                        $oimp->formadepago = "PLIN";
                        break;
                }
                $oimp->moneda = $fila['moneda'] === 'S' ? 'SOLES' : 'DOLARES';
                $oimp->importeletras = $cletras->ValorEnLetras($fila['impo'], $fila['moneda'] === 'S' ? 'SOLES' : 'DOLARES');
                $oimp->valorgravado = $fila['valor'];
                $oimp->igv = $fila['igv'];
                $oimp->total = $fila['impo'];
                $oimp->vigv = $fila['vigv'];
            }
            $i++;
        }
        $oimp->generapdf($rutapdf);
    }
    function imprimirticket($nidauto, $tipo, $rutapdf)
    {
        $ventas = new Ventas();
        $st = $ventas->consultardcto($nidauto, $tipo);
        $oimp = new Imprimir();
        $cletras = new Cletras();
        $i = 1;
        foreach ($st as $fila) {
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $fila['unid'],
                'descri' => $fila['descri'],
                'cant' => $fila['cant'],
                'prec' => $fila['prec'],
                'subtotal' => round($fila['cant'] * $fila['prec'], 2)
            );

            if ($i == 1) {
                $oimp->empresa = $fila['empresa'];
                $oimp->rucempresa = $fila['rucempresa'];
                $oimp->direccionempresa = $fila['ptop'];

                switch ($fila['tdoc']) {
                    case '01':
                        $oimp->tipocomprobante = ' FACTURA ELECTRONICA';
                        break;
                    case '03':
                        $oimp->tipocomprobante = ' BOLETA DE VENTA ELECTRONICA ';
                        break;
                    case '20':
                        $oimp->tipocomprobante = ' NOTA DE VENTA      ';
                        break;
                    default:
                        $oimp->tipocomprobante = ' NOTA DE CREDITO      ';
                        break;
                }
                $oimp->numero = $fila['serie'] . '-' . $fila['numero'];
                $oimp->serie = $fila['serie'];
                $oimp->ndoc = $fila['ndoc'];
                $dfecha = date("d/m/Y", strtotime($fila['dfecha']));
                $oimp->fecha = $dfecha;
                $hora = date("H:i:s", strtotime($fila['fusua']));
                $oimp->hora = $hora;
                $oimp->fechavto = date("d/m/Y", strtotime($fila['fvto']));
                $datetime1 = date_create($fila['fvto']);
                $datetime2 = date_create($fila['dfecha']);
                $interval = date_diff($datetime2, $datetime1);
                $oimp->dias = $interval->days;
                $oimp->tdoc = $fila['tdoc'];
                $oimp->ruccliente = $fila['nruc'];
                $oimp->dnicliente = $fila['ndni'];
                $oimp->guiaremision = $fila['ndo2'];
                $oimp->referencia = isset($fila['deta']) ? $fila['deta'] : '';
                if ($tipo == 'S' or $tipo == 'T') {
                    $oimp->detraccion = $fila['rcom_detr'];
                } else {
                    $oimp->detraccion = '0';
                }
                $oimp->optigv = $fila['incl'];
                $oimp->cliente = $fila['razo'];
                $oimp->vendedor = $fila['vendedor'];
                $oimp->direccioncliente =  ($fila['direccion']);
                switch ($fila['form']) {
                    case 'E':
                        $formapago = "CONTADO";
                        break;
                    case 'C':
                        $formapago = "CREDITO";
                        break;
                    case 'D':
                        $formapago = "DEPOSITO";
                        break;
                    case 'T':
                        $formapago = "TARJETA";
                        break;
                    case 'Y':
                        $formapago = "YAPE";
                        break;
                    case 'P':
                        $formapago = "PLIN";
                        break;
                }
                $oimp->formadepago = $formapago;
                $oimp->moneda = $fila['moneda'] === 'S' ? 'SOLES' : 'DOLARES';
                $oimp->vuelto = $fila['rcom_vuelto'];
                $oimp->importeletras = $cletras->ValorEnLetras($fila['impo'], $fila['moneda'] === 'S' ? 'SOLES' : 'DOLARES');
                $oimp->valorgravado = $fila['valor'];
                $oimp->igv = $fila['igv'];
                $oimp->total = $fila['impo'];
                $oimp->vigv = $fila['vigv'];
            }
            $i++;
        }
        $oimp->generarpdfticket($rutapdf);
    }
    function enviarboletas(Request $request)
    {
        setempresa($request->get("empresa"));
        $app = Application::getInstance();
        $respuesta = $app->envio->ObtenerBoletasResumidas($request->get('fecha'));
        if ($respuesta['estado'] == '0') {
            $oapi = new apifacturacion();
            $emisor = $respuesta['emisor'];
            $detalle = $respuesta['detalle'];
            $respuestaticket = $oapi->ConsultarTicket($emisor, $respuesta['ticket'], $respuesta['nombrexml'], $detalle);
            if ($respuestaticket['estado'] == '0') {
                $app->envio->crpta = $respuestaticket['mensaje'];
                $app->envio->cdr = $respuestaticket['cdr'];
                $app->envio->GrabaCDRTicket(trim($respuesta['ticket']), $detalle);
                //$oenvio->crpta = $crpta;
                // $oenvio->cdr = $cdr;
                // $oenvio->GrabaCDRTicket(trim($ticket), $detalle);
                return json_encode($respuestaticket);
            } else {
                return json_encode($respuestaticket);
            }
        } else {
            return json_encode($respuesta);
        }
    }
    public function eliminarticket(Request $request)
    {
        setempresa($request->get("empresa"));
        $cticket = $request->get('cticket');
        $app = Application::getInstance();
        $rpta = $app->envio->eliminarticket($cticket);
        return json_encode($rpta);
    }
    function indexAnular()
    {
        $titulo = 'Anular Documentos';
        return view('cpe/indexAnular', ["titulo" => $titulo]);
    }
    function buscarDetalleDocumento(Request $request)
    {
        $venta = new Ventas();
        $guiarm = new GuiaRemitente();
        $compra = new Compra();
        $caja = new Caja();
        $txtNumeroDocumento = $request->get("txtNumeroDocumento");
        $cmbTipoDocumento = $request->get("cmbdcto");
        $cmbTipoMovimiento = $request->get("cmbTipoMovimiento");

        if ($cmbTipoMovimiento == 'V') {
            switch ($cmbTipoDocumento) {
                case '09':
                    $listado = $guiarm->listarguiaresumen($txtNumeroDocumento, $cmbTipoDocumento, $cmbTipoMovimiento);
                    break;
                case '31':
                    $listado = $guiarm->listarguiaresumen($txtNumeroDocumento, $cmbTipoDocumento, $cmbTipoMovimiento);
                    break;
                default:
                    $listado = $venta->buscarDetalleAnularVenta($txtNumeroDocumento, $cmbTipoDocumento, $cmbTipoMovimiento);
                    break;
            }
        } else {
            if ($cmbTipoMovimiento == 'C') {
                switch ($cmbTipoDocumento) {
                    case '09':
                        $listado = $guiarm->listarguiaresumen($txtNumeroDocumento, $cmbTipoDocumento, $cmbTipoMovimiento);
                        break;
                    case '31':
                        $listado = $guiarm->listarguiaresumen($txtNumeroDocumento, $cmbTipoDocumento, $cmbTipoMovimiento);
                        break;
                    default:
                        $listado = $compra->buscarDetalleAnularCompra($txtNumeroDocumento, $cmbTipoDocumento, $cmbTipoMovimiento);
                        break;
                }
            } else {
                if ($cmbTipoMovimiento == 'T') {
                    switch ($cmbTipoDocumento) {
                        case '09':
                            $listado = $guiarm->listarguiatraspaso($txtNumeroDocumento, $cmbTipoMovimiento);
                            break;
                    }
                } else {
                    if ($cmbTipoMovimiento == 'CE') {
                        $listado = $caja->buscarcajaparaanular($txtNumeroDocumento);
                        $listado = $listado['lista'];
                    } else {
                        if ($cmbTipoMovimiento == 'CB') {
                            $listado = $caja->buscarcajabancoparanular($txtNumeroDocumento);
                            $listado = $listado['lista'];
                        }
                    }
                }
            }
        }
        return view('cpe/detalleAnular', ["listado" => $listado]);
    }
    function eliminarDocumento(Request $request)
    {
        $venta = new Ventas();
        $guiarm = new GuiaRemitente();
        $producto = new Producto();
        $caja = new Caja();
        $compra = new Compra();
        $usua = $request->get("txtUsuario");
        $pass = $request->get("txtPassword");
        $idauto = $request->get("txtIdauto");
        $tdoc = $request->get("txttdoc");
        $cmbTipoMovimiento = $request->get("cmbTipoMovimiento");
        $ousuario = new Usuario();
        $valor = $ousuario->verificarUsuarioLogueado(trim($usua), $pass);
        if (!empty($valor[0]['idusua'])) {
            if ($cmbTipoMovimiento == 'V') {
                switch ($tdoc) {
                    case '09':
                        $guiarm->anularguia($idauto, $request->get("txttdoc"));
                        break;
                    case '31':
                        $guiarm->anularguia($idauto, $request->get("txttdoc"));
                        break;
                    case 'AJ':
                        $rpta = $producto->anularTraspaso($idauto);
                        break;
                    default:
                        $venta->anularVentaPorID($idauto, $valor[0]['idusua']);
                        break;
                }
            } else {
                if ($cmbTipoMovimiento == 'C') {
                    switch ($tdoc) {
                        case '09':
                            $guiarm->anularguia($idauto, $request->get("txttdoc"));
                            break;
                        default:
                            $compra->anularcompraxid($idauto, $valor[0]['idusua']);
                            break;
                    }
                } else {
                    if ($cmbTipoMovimiento == 'T') {
                        switch ($tdoc) {
                            case '09':
                                $guiarm->anularguia($idauto, $request->get("txttdoc"));
                                break;
                        }
                    } else {
                        if ($cmbTipoMovimiento == 'CE') {
                            $rpta = $caja->anularmvtocaja($idauto, $valor[0]['idusua']);
                        } else {
                            if ($cmbTipoMovimiento == 'CB') {
                                $rpta = $caja->anularcajabanco($idauto, $valor[0]['idusua']);
                            }
                        }
                    }
                }
            }
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        } else {
            return response()->json(['message' => 'Las credenciales no son correctas'], 422);
        }
    }
    function bajaDocumento(Request $request)
    {
        $usua = $request->get("txtUsuario");
        $pass = $request->get("txtPassword");
        $idauto = $request->get("txtIdauto");
        $ousuario = new Usuario();
        $valor = $ousuario->verificarUsuarioLogueado(trim($usua), $pass);
        if (!empty($valor[0]['idusua'])) {
            $datos = array(
                "rucempresa" => session()->get("gene_nruc"),
                "empresa" => session()->get("gene_empresa"),
                "direccion" => session()->get("gene_ptop"),
                "pais" => "PE",
                "departamento" => session()->get("gene_Ciudad"),
                "provincia" => session()->get("gene_Ciudad"),
                "distrito" => session()->get("gene_Distrito"),
                "ubigeo" => session()->get("gene_ubigeo"),
                "usol" => session()->get("gene_gene_usol"),
                "clavesol" => session()->get("gene_gene_csol"),
                "idauto" => $idauto,
                "ndoc" => $request->get("txtndocbaja"),
                "fecha" => $request->get("txtfecha"),
                "tdoc" => $request->get("txttdoc"),
                "certificado" => session()->get("gene_gene_cert"),
                "clavecertificado" => session()->get("gene_claveCertificado")
            );
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'app88.test/enviobaja.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($datos),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            echo json_decode($response, true);
            return;

            $rpta = json_decode($response, true);

            if ($rpta['estado'] == '0') {
                return response()->json(['message' => 'Eliminado correctamente'], 200);
            } else {
                return response()->json(['message' => $rpta['estado'] . ' ' . $rpta['mensaje']], 200);
            }
        } else {
            return response()->json(['message' => 'Las credenciales no son correctas'], 422);
        }
    }
}
