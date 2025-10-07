<?php

namespace App\Controllers;

use App\Models\GuiaRemitente;
use App\Models\GuiaTransportista;
use App\Models\Pedido;
use App\Models\Ventas;
use App\Models\Usuario;
use Core\Routing\Controller;
use Valitron\Validator;
use App\Services\CarritoService;
use App\Services\CarritoServiceCanje;
use Core\Clases\Imprimir;
use Core\Http\Request;
use Core\Clases\Cletras;
use Exception;

class VentasController extends Controller
{
    function indexovtas()
    {
        $serie = \session()->get('cndocv', '');
        $num = \session()->get('numv', '');
        $idventa = \session()->get('idventa', 0);
        if ($idventa > 0) {
            $titulo = 'Editar-Servicios';
        } else {
            $titulo = 'Vta Servicios';
        }
        $datosclientev = array();
        $datosclientev = array(
            'idcliev' => \session()->get('idcliev', 0),
            'razov' => \session()->get('razov', ''),
            'ruccliev' => \session()->get('ruccliev', 0),
            'dnicliev' => \session()->get('dnicliev', 0),
            'direcliev' => \session()->get('direcliev', ''),
            'tdocv' => \session()->get('tdocv', 0),
            'ndoc' => \session()->get('ndoc', 0),
            'cndocv' => $serie,
            'numv' => $num,
            'ndo2v' => \session()->get('ndo2v', ''),
            'almv' => \session()->get('almv', ''),
            'fechv' => \session()->get('fechv', ''),
            'monev' => \session()->get('monev', ''),
            'formv' => \session()->get('formv', ''),
            'fechvv' => \session()->get('fechvv', ''),
            'idvenv' => \session()->get('idvenv', ''),
        );
        $gene_detra = session()->get('gene_gene_detr', '');
        return view('ventas/index', ['titulo' => $titulo, 'datosclientev' => $datosclientev, 'serie' => $serie, 'num' => $num, 'idventa' => $idventa, 'detalle' => [], 'gene_detra' => $gene_detra]);
    }
    function regvtasp()
    {
        $titulo = "Registro de ventas";
        return view('ventas/informes/indexlistarple', ["titulo" => $titulo]);
    }
    function regvtasple(Request $request)
    {
        // $ventas = new Ventas();
        // $listado = $ventas->registroventasple($request->get('mes'), $request->get('ano'));
        $datapost = array('mes' => $request->get('mes'), 'ano' => $request->get('ano'), 'ruc' => $_SESSION['gene_nruc']);
        // return view('ventas/informes/listarple', ['listado' => $this->obtenerlistadople($datapost)]);
        return response()->json(['message' => 'Se logró listar correctamente', 'listado' =>  $this->obtenerlistadople($datapost)], 200);
    }
    function obtenerlistadople($datapost)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://companiasysven.com/API/listarple.php',
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
        // var_dump($response);
        $data = json_decode($response, true);
        return $data['result'];
    }
    function obtenerlistadonotascreditople($datapost)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://companiasysven.com/API/listarnotascreditople.php',
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
    function oventasresumidas()
    {
        $ctitulo = 'Ventas x Servicio';
        return view('ventas/re_vtas', [
            "titulo" => $ctitulo
        ]);
    }
    function mostraroventasresumidas(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $empresasel = $request->get("empresa");
        if (empty($empresasel)) {
            $nidt = 0;
        } else {
            $nidt = intval($empresasel);
        }
        $ventas = new Ventas();
        $listado = $ventas->mostraroventas($dfi, $dff, $nidt);
        return view('ventas/re_listavtasr', [
            "listado" => $listado
        ]);
    }
    function registrarovta(Request $request)
    {
        $ovalidar = $this->validar($request, 'O');
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }

        $ovtas = new Ventas();

        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "tdocv" => $request->get("tdocv"),
            "ndo2v" => $request->get("ndo2v"),
            "almv" => '1',
            "fechv" => $request->get("fechv"),
            "txtdireccion" => $request->get("txtdireccion"),
            "txtruccliente" => $request->get("txtruccliente"),
            "txtdnicliente" => $request->get("txtdnicliente"),
            "razov" => $request->get('razov'),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "cliente" => $request->get("txtcliente"),
            "ruccliente" => $request->get("txtruccliente"),
            "dnicliente" => $request->get("dnicliente"),
            "nidus" => session()->get('usuario_id'),
            "ndias" => $request->get("ndias"),
            "txtdetraccion" => $request->get("txtdetraccion"),
            "nitem" => str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT)
        );

        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $registro = $ovtas->grabaroVentaGeneral($cabecera, $detalle);

        if ($registro['estado'] == '1') {
            $this->limpiarSesionOvta();
            $_SESSION['datosovta'] = $cabecera;
            $_SESSION['detallev'] = $detalle;
            $_SESSION['ndoc'] = $registro['ndoc'];
            return response()->json(['message' => 'Se registro correctamente', 'ndoc' => $registro['ndoc']], 200);
        } else {
            return response()->json(['message' => 'Error al registrar venta', 'error' => $registro['mensaje']], 422);
        }
    }
    function modificarovta(Request $request)
    {
        $ovalidar = $this->validar($request, 'O');
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        $venta = new Ventas();
        $deta =  "";
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "tdocv" => $request->get("tdocv"),
            "ndoc" => $request->get("ndoc"),
            "ndo2v" => $request->get("ndo2v"),
            "almv" => '1',
            "fechv" => $request->get("fechv"),
            "fechvv" => $request->get("fechvv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "txtdetraccion" => $request->get("txtdetraccion"),
            "nidus" => session()->get('usuario_id'),
            "nidautov" => $request->get("idautov"),
            "detav" => $deta,
            "nitemsv" => 0
        );

        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        $rpta = $venta->actualizarOVenta($cabecera, $detalle);
        if ($rpta['estado'] == '1') {
            $this->limpiarSesionOvta();
            // $cvista = \retornavista('ventas', 'detalle');
            return response()->json(['message' => 'Se modificó correctamente'], 200);
        } else {
            return response()->json(['message' => 'Error al modificar Venta', 'error' => $rpta['mensaje']], 422);
        }
    }

    function buscarOVentaPorID($idauto)
    {
        $venta = new Ventas();
        $nroventa = "";
        $idautov = $idauto;
        $this->limpiarSesionOvta();
        // $carritov = session()->get('carritov', []);
        $lista = $venta->mostrsroventas($idauto);
        session()->set('idventa', $idautov);
        $datosclientev = array();
        $i = 0;
        foreach ($lista as $item) {
            if ($i == 0) {
                $razo = str_replace('"', ' ', $item['razo']);
                $datosclientev = array(
                    'idauto' => $item['idauto'],
                    'almv' => $item['alma'],
                    'fechv' => $item['fech'],
                    'fvto' => $item['fvto'],
                    'ndoc' => $item['ndoc'],
                    'formv' => $item['form'],
                    'tdocv' => $item['tdoc'],
                    'dolarv' => $item['dolar'],
                    'tipov' => $item['tipo'],
                    'monev' => $item['mone'],
                    'razov' => $razo,
                    'direcliev' => $item['dire'],
                    'idcliev' => $item['idclie'],
                    'ruccliev' => $item['nruc'],
                    'ndo2v' => $item['ndo2'],
                    'idvenv' => $item['codv'],
                    'detallev' => (isset($item['detalle'])) ? $item['detalle'] : '',
                    'rcom_detr' => $item['rcom_detr'],
                    'subtotalv' => $item['valor'],
                    'igvv' => $item['igv'],
                    'impov' => $item['impo']
                );
                $nroventa = $item['ndoc'];
            }
        }
        $detalleg = $venta->mostrardetalloventas($idauto);
        foreach ($detalleg as $i) {
            $detalle[] = array(
                'nreg' => $i["nreg"],
                'descri' => $i["descri"],
                'cant' => floatval($i['cant']),
                'precio' => floatval($i['prec']),
                'unidad' => $i['unidad'],
                'subt' => floatval($i['cant']) * floatval($i['prec']),
                'activo' => 'A'
            );
        }
        // session()->set('carritov', $carritov);
        session()->set('datosclientev', $datosclientev);

        $datosclientev = session()->get('datosclientev', []);

        $titulo = 'Actualizar Venta' . ' ' . $nroventa;
        session()->set('nroventa', $nroventa);

        $serie = substr($nroventa, 0, 4);
        $num = substr($nroventa, 4);

        session()->set('idventa', $idauto);

        $cvista = \retornavista('ventas', 'index');

        \session()->set('idcliev', $datosclientev['idcliev']);
        \session()->set('razov',  $datosclientev['razov']);
        \session()->set('ruccliev',  $datosclientev['ruccliev']);
        \session()->set('tdocv',  $datosclientev['tdocv']);
        \session()->set('ndoc',  $datosclientev['ndoc']);
        \session()->set('cndocv',  $serie);
        \session()->set('numv', $num);
        \session()->set('direcliev',  $datosclientev['direcliev']);
        \session()->set('ndo2v',  $datosclientev['ndo2v']);
        \session()->set('almv',  $datosclientev['almv']);
        \session()->set('formv',  $datosclientev['formv']);
        \session()->set('monev',  $datosclientev['monev']);
        \session()->set('fechv',  $datosclientev['fechv']);
        \session()->set('idvenv',  $datosclientev['idvenv']);
        $gene_detra = session()->get('gene_gene_detr', '');
        return view($cvista, ['titulo' => $titulo, 'datosclientev' => $datosclientev, 'idventa' => $idautov, 'serie' => $serie, 'num' => $num, 'detalle' => $detalle, 'gene_detra' => $gene_detra]);
    }
    function limpiarSesionOvta()
    {
        session()->remove('carritov');
        session()->remove('idventa');
        session()->remove('direcliev');
        session()->remove('idcliev');
        session()->remove('razov');
        session()->remove('ruccliev');
        session()->remove('tdocv');
        session()->remove('cndocv');
        session()->remove('numv');
        session()->remove('ndo2v');
        session()->remove('almv');
        session()->remove('formv');
        session()->remove('monev');
        session()->remove('fechv');
        session()->remove('fechvv');
        session()->remove('idvenv');
        session()->remove('optigv');
    }
    function grabarSesion(Request $request)
    {
        \session()->set('idcliev', $request->get('idcliev'));
        \session()->set('razov', $request->get('razov'));
        \session()->set('ruccliev', $request->get('ruccliev'));
        \session()->set('dnicliev', $request->get('dnicliev'));
        \session()->set('direcliev', $request->get('direcliev'));
        \session()->set('tdocv', $request->get('tdocv'));
        \session()->set('ndoc', $request->get('ndoc'));
        \session()->set('numv', $request->get('numv'));
        \session()->set('ndo2v', $request->get('ndo2v'));
        \session()->set('almv', $request->get('almv'));
        \session()->set('fechv', $request->get('fechv'));
        \session()->set('monev', $request->get('monev'));
        \session()->set('formv', $request->get('formv'));
        \session()->set('fechvv', $request->get('fechvv'));
        \session()->set('optigv', $request->get('optigv'));
        \session()->set('idvenv', $request->get('idvenv'));
        \session()->set('txtreferencia', $request->get('txtreferencia'));
    }
    function limpiarvta()
    {
        $this->limpiarSesionVtad();
        session()->set('moneda', 'S');
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ventasd', 'detalle');
        return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function index()
    {
        $titulo = "Ventas";
        $serie = \session()->get('cndocv', '');
        $num = \session()->get('numv', '');
        $idventa = \session()->get('idventa', 0);
        $datosclientev = array();
        $datosclientev = array(
            'idcliev' => \session()->get('idcliev', 0),
            'razov' => \session()->get('razov', ''),
            'ruccliev' => \session()->get('ruccliev', 0),
            'tdocv' => \session()->get('tdocv', 0),
            'cndocv' => $serie,
            'numv' => $num,
            'ndo2v' => \session()->get('ndo2v', ''),
            'almv' => \session()->get('almv', $_SESSION['idalmacen']),
            'fechv' => \session()->get('fechv', ''),
            'monev' => \session()->get('monev', ''),
            'formv' => \session()->get('formv', ''),
            'fechvv' => \session()->get('fechvv', ''),
            'idvenv' => \session()->get('idvenv', ''),
            'optigv' => \session()->get('optigv', 'I'),
            'txtreferencia' => \session()->get('txtreferencia', '')
        );
        session()->set("mensajesunat", "");
        // session()->set("vista", "R");
        return view('ventasd/index', ['titulo' => $titulo, 'datosclientev' => $datosclientev, 'serie' => $serie, 'num' => $num, 'idventa' => $idventa]);
    }
    function listarDetalle()
    {
        $idventa = \session()->get('idventa', 0);
        if ($idventa > 0) {
            $btn = 'Modificar';
        } else {
            $btn = 'Grabar';
        }
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        return view('ventasd/detalle', ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
    function verificarsiyaesta($idart)
    {
        if (CarritoService::siestaventas($idart)) {
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
        //         'message' => 'Producto ya agregado',
        //         'rpta' => 'N'
        //     ];
        //     return response()->json($data, 422);
        // }
        $stock = $request->get("stock");
        $preciomin = min($request->get("precio1"), $request->get("precio2"), $request->get("precio3"));
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es Obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es Obligatorio');
        $validar->rule("numeric", "txtprecio")->message('El Precio debe ser Numerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser Númerico');
        $validar->rule("min", "txtcantidad", 1)->message('La Cantidad debe de ser mayor a 0');
        if ($_SESSION['config']['validarstock'] == 'S') {
            $validar->rule("max", "txtcantidad", $stock)->message("Stock no disponible");
        }
        $validar->rule("min", "txtprecio", $preciomin)->message("Precio no permitido");
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
            'tipoproducto' => $request->get('tipoproducto'),
            'costo' => $request->get('costo'),
            'presentaciones' => ($request->get('presentaciones')),
            'cantequi' => ($request->get('cantequi')),
            'presseleccionada' => $request->get('presseleccionada'),
            'caant' => 0
        );

        CarritoService::agregarItemVenta($producto, $request->get('cmbmoneda'));
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);

        $carritov = session()->get('carritov', []);
        $cvista = \retornavista('ventasd', 'detalle');
        return view($cvista, [
            'carritov' => $carritov, 'total' => $total, 'items' => $numero_items
        ]);
    }
    function soloItem(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'descri' => ($request->get('txtdescri')),
            'unidad' => ($request->get('unidad')),
            'cantidad' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtcantidad')),
            'precio' => floatval($request->get('txtprecio') <= 0.00  ? 1 : $request->get('txtprecio')),
            'cantequi' => $request->get('cantequi'),
            'presseleccionada' => $request->get('presseleccionada'),
            'lote' => $request->get('lote'),
            'fechavto' => $request->get('fechavto'),
            'activo' => 'A'
        );
        CarritoService::editarProductoVenta($producto, $request->get('cmbmoneda'));
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function EditarLoteFechavto(Request $request)
    {
        $producto = array();
        $producto = array(
            'txtidart' => $request->get('txtidart'),
            'lote' => $request->get('txtlote'),
            'fechavto' => $request->get('txtfechavto')
        );
        CarritoService::editarProductoLoteFechavto($producto, $request->get('cmbmoneda'));
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function limpiarSesionVtad()
    {
        session()->remove('carritov');
        session()->remove('cliente');
        session()->remove('idventa');
        session()->remove('idcliev');
        session()->remove('razov');
        session()->remove('ruccliev');
        session()->remove('tdocv');
        session()->remove('cndocv');
        session()->remove('numv');
        session()->remove('ndo2v');
        session()->remove('almv');
        session()->remove('formv');
        session()->remove('monev');
        session()->remove('fechv');
        session()->remove('fechvv');
        session()->remove('idvenv');
        session()->remove('txtreferencia');
    }
    function limpiarsesionventarapida()
    {
        session()->remove('cliente');
        session()->remove('idventa');
        session()->remove('idcliev');
        session()->remove('razov');
        session()->remove('ruccliev');
        session()->remove('tdocv');
        session()->remove('cndocv');
        session()->remove('numv');
        session()->remove('ndo2v');
        session()->remove('almv');
        session()->remove('formv');
        session()->remove('monev');
        session()->remove('fechv');
        session()->remove('fechvv');
        session()->remove('idvenv');
        session()->remove('txtreferencia');
    }
    function cargarrespaldo(Request $request)
    {
        $respaldocarrito = json_decode($request->get("respaldocarrito"));
        $respaldocarrito = json_decode(json_encode($respaldocarrito), true);
        $producto = [];
        $e = 0;
        foreach ($respaldocarrito as $i) {
            $presentaciones = $i['tdpresc'];
            $presentaciones = explode("-", $presentaciones);

            $producto['coda'] = $i['tdcoda'];
            $producto['descri'] = $i['tddescri'];
            $producto['unidad'] = $presentaciones[0] . "-" . $presentaciones[1];
            $producto['cantidad'] = $i['tdcant'];
            $producto['precio'] = $i['tdprec'];
            $producto['stock'] = 0;
            $producto['precio1'] = 0;
            $producto['precio2'] = 0;
            $producto['precio3'] = 0;
            $producto['costo'] = 0;
            $producto['nreg'] = $e;
            $producto['caant'] = 0;
            $producto['tipoproducto'] = 'K';
            $producto['idcliente'] = 0;
            $producto['presentaciones'] = [];
            $producto['cantequi'] = $presentaciones[1];
            $producto['presseleccionada'] = $presentaciones[2];
            $producto['lote'] = '';
            $producto['fechavto'] = date('Y-m-d');
            $producto['activo'] = 'A';
            $e++;
            CarritoService::agregarItemVenta($producto, 'S');
        }
    }
    function registrar(Request $request)
    {
        if (!empty($request->get('respaldo'))) {
            if (empty($_SESSION["carritov"])) {
                $data = ["errors" => ['No hay productos añadidos en la venta'], "estado" => 2];
                return response()->json($data, 200);
            }
        }
        $ovalidar = $this->validar($request);
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        $venta = new Ventas();
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "tdocv" => $request->get("tdocv"),
            "razov" => $request->get("razov"),
            "txtdireccion" => $request->get("txtdireccion"),
            "txtruccliente" => $request->get("txtruccliente"),
            "txtdnicliente" => $request->get("txtdnicliente"),
            "ndo2v" => $request->get("ndo2v"),
            "almv" => $request->get("almv"),
            "fechv" => $request->get("fechv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "nidus" => session()->get('usuario_id'),
            "nitem" => str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT),
            'optigv' => $request->get("optigv"),
            "txtreferencia" => $request->get("txtreferencia"),
            'txtefectivo' => $request->get('txtefectivo'),
            'txtpago' => $request->get('txtpago'),
            'txtvuelto' => $request->get('txtvuelto')
        );

        $registro = $venta->grabarVentaGeneral($cabecera);

        if ($registro['estado'] == 1) {
            $carritodetalle = [];
            $carritov = session()->get('carritov', []);
            foreach ($carritov as $c) {
                if ($c['activo'] == 'A') {
                    array_push($carritodetalle, $c);
                }
            }
            $_SESSION['datosovta'] = $cabecera;
            $_SESSION['detallev'] =  $carritodetalle;
            $_SESSION['ndoc'] = $registro['ndoc'];
            $this->limpiarSesionVtad();
            // $carritov = session()->get('carritov', []);
            $rpta = array('mensaje' => "Se genero la venta satisfactoriamente", "ndoc" => $registro['ndoc'], "estado" => '1');
            return json_encode($rpta, 200);
        } else {
            return response()->json(['message' => 'Error al registrar Venta', 'error' => $registro['mensaje']], 422);
        }
    }
    function modificar(Request $request)
    {
        $ovalidar = $this->validar($request);
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        $numeroDocumento = $_SESSION['nroventa'];
        $venta = new Ventas();
        $deta =  "";
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "tdocv" => $request->get("tdocv"),
            "cndocv" => $numeroDocumento,
            "ndo2v" => $request->get("ndo2v"),
            "razov" => $request->get("razov"),
            "almv" => $request->get("almv"),
            "fechv" => $request->get("fechv"),
            "fechvv" => $request->get("fechvv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "nidus" => session()->get('usuario_id'),
            "nidautov" => $request->get("idautov"),
            'optigv' => $request->get('optigv'),
            "detav" => $deta,
            "nitemsv" => str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT),
            "txtreferencia" => $request->get("txtreferencia"),
            'txtefectivo' => $request->get('txtefectivo'),
            'txtpago' => $request->get('txtpago')
        );
        $rpta = $venta->actualizarVenta($cabecera);
        if ($rpta['estado'] == '1') {
            $this->limpiarSesionVtad();
            session()->set('carritov', []);
            $carritov = session()->get('carritov', []);
            $total = number_format(CarritoService::totalVenta(), 2, '.', '');
            $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
            $cvista = \retornavista('ventasd', 'detalle');
            return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items, 'numeroDocumento' => $numeroDocumento]);
        } else {
            return response()->json(['message' => 'Error al modificar la venta'], 422);
        }
    }
    function quitaritem(Request $request)
    {
        $pos = $request->get('indice');
        CarritoService::quitarItemVenta($pos);
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ventasd', 'detalle');
        return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function buscarVentaPorID($idauto)
    {
        // $_SESSION['carritovv'] = empty($_SESSION['carritov']) ? [] : $_SESSION['carritov'];
        $venta = new Ventas();
        $nroventa = "";
        $this->limpiarSesionVtad();
        // $carritov = session()->get('carritov', []);
        $lista = $venta->buscarVentaPorId($idauto);
        session()->set('idventa', $idauto);
        $datosclientev = array();
        $i = 0;
        foreach ($lista as $item) {
            if ($i == 0) {
                $razo = str_replace('"', ' ', $item['razo']);
                $datetime1 = date_create($item['fech']);
                $datetime2 = date_create($item['fvto']);
                $interval = date_diff($datetime2, $datetime1);
                // $oimp->dias=$fila['dias'];
                $ndias = $interval->days;
                $datosclientev = array(
                    'idauto' => $item['idauto'],
                    'almv' => $item['alma'],
                    'fechv' => $item['fech'],
                    'fvto' => $item['fvto'],
                    'formv' => $item['form'],
                    'tdocv' => $item['tdoc'],
                    'dolarv' => $item['dolar'],
                    'tipov' => $item['tipo'],
                    'monev' => $item['mone'],
                    'razov' => $razo,
                    'idcliev' => $item['idclie'],
                    'ruccliev' => $item['nruc'],
                    'dnicliev' => $item['ndni'],
                    'ndo2v' => $item['ndo2'],
                    'idvenv' => $item['codv'],
                    'optigv' => $item['incl'],
                    'dias' => $ndias,
                    'txtreferencia' => (isset($item['deta'])) ? $item['deta'] : '',
                    'subtotalv' => $item['valor'],
                    'igvv' => $item['igv'],
                    'impov' => $item['impo'],
                    'mensajesunat' => $item['rcom_mens']
                );
                $nroventa = $item['ndoc'];
            }
            $c[] = array(
                'coda' => $item["Coda"],
                'descri' => $item["descri"],
                'unid' => $item['unid'],
                'cant' => $item['cant'],
                'prec' => $item["prec"],
                'idkar' => $item["idkar"],
                'costo' => $item["costo"],
                'TAlma' => $item['TAlma'],
                'pre1' => $item['pre1'],
                'pre2' => $item['pre2'],
                'tipro' => $item['tipro'],
                'pre3' => $item['pre3'],
                'idclie' => $item['idclie'],
                'epta_idep' => empty($item['epta_idep']) ? 0 : $item['epta_idep'],
                'pres_desc' => empty(trim($item['pres_desc'])) ? 'UNID' : $item['pres_desc'],
                'epta_cant' => empty($item['epta_cant']) ? 1 : $item['epta_cant'],
                'epta_prec' => empty($item['epta_prec']) ? $item['prec'] : $item['epta_prec'],
                'presseleccionada' => empty($item['kar_epta']) ? 0 : $item['kar_epta'],
                'kar_equi' => empty($item['kar_equi']) ? 1 : $item['kar_equi'],
                'fechavto' => empty($item['kar_fvto']) ? date('Y-m-d') : $item['kar_fvto'],
                'lote' => empty($item['kar_lote']) ? '' : $item['kar_lote'],
                'caant' => $item['cant'],
                'activo' => 'A'
            );
        }
        $ltagrupada = array();
        foreach ($c as $k => $producto) {
            $idart = $producto["idkar"];
            $ltagrupada[$idart][] = $producto;
        }

        // echo '<pre>';
        // var_dump($ltagrupada);
        // echo '</pre>';
        // return;

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

            $carritov[] = array(
                'coda' => $items[0]["coda"],
                'descripcion' => $items[0]["descri"],
                'unidad' => $items[0]['unid'],
                'cantidad' => $items[0]['cant'],
                'precio' => $items[0]["prec"],
                'nreg' => $items[0]["idkar"],
                'costo' => $items[0]["costo"],
                'stock' => $items[0]['TAlma'],
                'precio1' => $items[0]['pre1'],
                'precio2' => $items[0]['pre2'],
                'tipoproducto' => $items[0]['tipro'],
                'precio3' => $items[0]['pre3'],
                'idclie' => $items[0]['idclie'],
                'presentaciones' => json_encode($presentaciones),
                'presseleccionada' => $items[0]['presseleccionada'],
                'cantequi' => $items[0]['kar_equi'],
                'caant' => $items[0]['caant'],
                'lote' => $items[0]['lote'],
                'fechavto' => $items[0]['fechavto'],
                'activo' => 'A'
            );
        }

        session()->set('opigv', 'N'); //ESTO PARA QUE SOLO SE VEA EL IGV DE MANERA VISUAL
        session()->set('moneda', 'SI'); //(SIGNIFICA QUE YA HA SIDO SELECCIONADA) ESTO PARA QUE SOLO SE VEA LA MONEDA DE MANERA VISUAL
        session()->set('carritov', $carritov);
        session()->set('datosclientev', $datosclientev);
        session()->set("mensajesunat", $datosclientev['mensajesunat']);

        $carritov = session()->get('carritov', []);
        $datosclientev = session()->get('datosclientev', []);

        $titulo = 'Actualizar Venta' . ' ' . $nroventa;
        session()->set('nroventa', $nroventa);

        $serie = substr($nroventa, 0, 4);
        $num = substr($nroventa, 4);

        session()->set('idventa', $idauto);

        $cvista = \retornavista('ventasd', 'index');

        \session()->set('idcliev', $datosclientev['idcliev']);
        \session()->set('razov',  $datosclientev['razov']);
        \session()->set('ruccliev',  $datosclientev['ruccliev']);
        \session()->set('tdocv',  $datosclientev['tdocv']);
        \session()->set('cndocv',  $serie);
        \session()->set('numv', $num);
        \session()->set('ndo2v',  $datosclientev['ndo2v']);
        \session()->set('almv',  $datosclientev['almv']);
        \session()->set('formv',  $datosclientev['formv']);
        \session()->set('monev',  $datosclientev['monev']);
        \session()->set('fechv',  $datosclientev['fechv']);
        \session()->set('idvenv',  $datosclientev['idvenv']);
        \session()->set('txtreferencia',  $datosclientev['txtreferencia']);
        \session()->set("vista", "E");
        return view($cvista, ['titulo' => $titulo, 'datosclientev' => $datosclientev, 'idventa' => $idauto, 'serie' => $serie, 'num' => $num, 'carritov' => $carritov]);
    }
    function ventasresumidas()
    {
        $ctitulo = 'Reporte de ventas';
        return view('ventas/informes/indexlistarvtas', [
            "titulo" => $ctitulo
        ]);
    }
    function mostrarventasresumidas(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $tipovta = $request->get("tipovta");
        $cmbformap = $request->get("cmbFormaP");
        $cmbmoneda = $request->get("cmbmoneda");
        $cmbtdoc = $request->get("cmbtdoc");
        $cmbalmacen = $request->get("cmbAlmacen");
        $ventas = new Ventas();
        $listado = $ventas->mostrarventas($dfi, $dff, $tipovta, $cmbformap, $cmbmoneda, $cmbtdoc, $cmbalmacen);
        return view('ventas/informes/listarvtas', [
            "listado" => $listado
        ]);
        // return response()->json(['message' => 'Se logró listar correctamente', 'listado' => $listado], 200);
    }
    function indexvtasanuladas()
    {
        $ctitulo = 'Ventas Anuladas';
        return view('ventas/informes/indexlistarvtasanuladas', [
            "titulo" => $ctitulo
        ]);
    }
    function listarvtasanuladas(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbtdoc = $request->get("cmbtdoc");
        $cmbalmacen = $request->get("cmbAlmacen");
        $ventas = new Ventas();
        $listado = $ventas->mostrarvtasanuladas($dfi, $dff, $cmbtdoc, $cmbalmacen);
        return view('ventas/informes/listarvtasanuladas', [
            "listado" => $listado
        ]);
        // return response()->json(['message' => 'Se logró listar correctamente', 'listado' => $listado], 200);
    }
    function imprimirdirecto()
    {
        $fila = $_SESSION['datosovta'];
        $detalle = $_SESSION['detallev'];
        $oimp = new Imprimir();
        $cletras = new Cletras();
        $i = 1;
        foreach ($detalle as $item) {
            $subtotal = (floatval($item['cantidad']) * floatval($item['precio']) > 0) ?  round($item['cantidad'] * $item['precio'], 2) : 0;
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $item['unidad'],
                'descri' => $item['descripcion'],
                'cant' => $item['cantidad'],
                'prec' => $item['precio'],
                'subtotal' =>   $subtotal
            );
            // $tpeso += $item['cantidad'] * $item['precio'];
            if ($i == 1) {
                $oimp->empresa = session()->get('gene_empresa');
                $oimp->rucempresa = session()->get('gene_nruc');
                $oimp->direccionempresa = session()->get('gene_ptop');
                $oimp->tdoc =  $fila['tdocv'];
                switch ($fila['tdocv']) {
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
                // $oimp->tipocomprobante = $fila['tdoc'];
                $oimp->fecha = date("d/m/Y", strtotime($fila['fechv']));
                $oimp->hora = date("H:i:s");
                $oimp->guiaremision = $fila['ndo2v'];
                $oimp->optigv = $fila['optigv'];
                $oimp->referencia = $fila['txtreferencia'];
                // $oimp->fecha = $dfecha;
                $oimp->fechavto = date("d/m/Y", strtotime($fila['fechvv']));
                $datetime1 = date_create($fila['fechvv']);
                $datetime2 = date_create($fila['fechv']);
                $interval = date_diff($datetime2, $datetime1);
                $oimp->dias = $interval->days;
                $oimp->numero =  substr($_SESSION['ndoc'], 0, 4) . '-' . substr($_SESSION['ndoc'], 4, 8);
                // $oimp->dias=$fila['dias'];
                $oimp->cliente = $fila['razov'];
                $oimp->direccioncliente = $fila['txtdireccion'];
                switch ($fila['formv']) {
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
                $oimp->importeletras = $cletras->ValorEnLetras($fila['total'], $fila['monev'] === 'S' ? 'SOLES' : 'DOLARES');
                $oimp->valorgravado = $fila['subtotal'];
                $oimp->ruccliente = $fila['txtruccliente'];
                $oimp->dnicliente = $fila['txtdnicliente'];
                $oimp->igv = $fila['igv'];
                $oimp->vigv = session()->get('gene_igv');
                $oimp->total = $fila['total'];
                $oimp->vuelto = $fila['txtvuelto'];
                $rutapdf = 'descargas/' . $_SESSION['ndoc'] . '.pdf';
            }
            $i++;
        }
        # I ES PARA GUARDAR EN EL SERVIDOR
        # PARA DESCARGAR EL ARCHIVO ES D
        if ($_SESSION['gene_impresionticket'] == 'S') {
            $oimp->generarpdfticket($rutapdf, 'I');
        } else {
            $oimp->generapdf($rutapdf, 'I');
        }
        $_SESSION['datosovta'] = [];
        $_SESSION['detallev'] = [];
        $_SESSION['ndoc'] = "";
    }
    //Canje de guia remisión
    function indexcanjes()
    {
        $titulo = "Facturar Guías";
        return view('canjes/index', ['titulo' => $titulo]);
    }
    function listarDetallecanjesguias()
    {
        $carritov = session()->get('carritocanje', []);
        $total = number_format(CarritoServiceCanje::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceCanje::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        return view('canjes/detallecanje', ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function listarDetalleCanje(Request $request)
    {
        $guia = new GuiaRemitente();
        $idguia = $request->get('idguia');
        $kardex = $guia->consultarGuiaDetalle($idguia, 'V', 'I');
        foreach ($kardex as $item) {
            $idautov = $item['idautov'];
            $idautog = $item['idautog'];
            $cguia = $item['idgui'];
            $carritov[] = array(
                'coda' => $item["coda"],
                'descripcion' => $item["descri"],
                'unidad' => (isset($item['unid'])) ? $item['unid'] : 'UNID',
                'cantidad' => $item['cant'],
                'precio' => $item["pre3"],
                'nreg' => 0,
                'costo' => 0,
                'stock' => 0,
                'precio1' => 0,
                'precio2' => 0,
                'precio3' => $item["pre3"],
                'idclie' => 0,
                'activo' => 'A',
                'tipoproducto' => $item['tipoproducto'],
                'idkar' => $item['idkar'],
                // "pres_desc" => (isset($row['pres_desc'])) ? $row['pres_desc'] : 'UNID',
                // "epta_prec" => (isset($row['epta_prec'])) ? $row['epta_prec'] : $item['pre3'],
                // "epta_cant" => (isset($row['epta_cant'])) ? $row['epta_cant'] : '1',
                // "epta_idep" => (isset($row['epta_idep'])) ? $row['epta_idep'] : '0'
            );
        }
        $total = number_format(CarritoServiceCanje::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceCanje::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $gene_detra = session()->get('gene_gene_detr', '');
        // session()->set('carritocanje', $carritov);
        return view('canjes/detallecanje', [
            'carritov' => $carritov, 'total' => $total, 'items' => $numero_items,
            'guia' => $cguia, 'gene_detra' => $gene_detra, 'idautov' => $idautov, 'idautog' => $idautog
        ]);
    }
    function registrarCanje(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idcliev");
        $validar->rule("required", "tdocv");
        // $validar->rule("required", "almv");
        $validar->rule("required", "fechv");
        $validar->rule("required", "monev");
        $validar->rule("required", "formv");
        $validar->rule("required", "fechvv");
        $validar->rule("required", "idvenv");
        $validar->rule("required", "subtotal");
        $validar->rule("required", "igv");
        $validar->rule("required", "total");

        $datetime1 = date_create($request->get('fechvv'));
        $datetime2 = date_create($request->get('fechv'));
        $interval = date_diff($datetime2, $datetime1);
        if (!fechavalida(date('d/m/Y', strtotime($request->get('fechv'))))) {
            $data = ["errors" => ['Fecha de emisión no válida'], "estado" => 0];
            return $data;
        }
        if (!fechavalida(date('d/m/Y', strtotime($request->get('fechvv'))))) {
            $data = ["errors" => ['Fecha de vencimiento no válida'], "estado" => 0];
            return $data;
        }
        $ndias = $interval->days;
        if ($request->get("formv") == 'C') {
            if ($ndias <= 0) {
                $data = ["errors" => ['Es obligatorio los días de Crédito mayor a 0'], "estado" => 0];
                return $data;
            }
        }
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => "Sesión vacía"];
            return response()->json($data, 422);
        }
        if ($request->get('tdocv') == '03') {
            if ((floatval($request->get('total')) > 700) && (empty($request->get('txtdnicliente')))) {
                $data = ["errors" => ['No se puede registrar la venta, porque el cliente no tiene DNI'], "estado" => 0];
                return $data;
            }
        }

        $venta = new Ventas();
        $cabecera = array(
            "idautov" => $request->get("idautov"),
            "idautog" => $request->get("idautog"),
            "idcliev" => $request->get("idcliev"),
            "iddire" => $request->get("iddire"),
            "tdocv" => $request->get("tdocv"),
            "razov" => $request->get('razov'),
            "ndo2v" => $request->get("ndo2v"),
            // "almv" => $request->get("almv"),
            "txtdireccion" => $request->get("txtdireccion"),
            "txtruccliente" => $request->get("txtruccliente"),
            "txtdnicliente" => $request->get("txtdnicliente"),
            "fechv" => $request->get("fechv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "nidus" => session()->get('usuario_id'),
            "nitem" =>  $request->get("totalitems"),
            "dias" => $request->get("txtdias"),
            "optigv" => $request->get("optigv"),
            "txtreferencia" => $request->get("txtreferencia")
        );

        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        if ($this->validarDetalleCanje($detalle) == false) {
            return response()->json(['message' => 'No hay precio en el detalle'], 422);
        }

        $rpta = $venta->grabarVentaCanje($cabecera, $detalle);
        if ($rpta['estado'] == "1") {
            $carritocanje = $detalle;
            $this->limpiarSesionVtad();
            $_SESSION['idautov'] = "";
            $_SESSION['idautog'] = "";
            $_SESSION['datosovta'] = $cabecera;
            $_SESSION['detallev'] = $carritocanje;
            $_SESSION['ndoc'] = $rpta['ndoc'];
            return response()->json(['message' => 'Se registro correctamente', 'ndoc' => $rpta['ndoc']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje']], 422);
        }
    }
    //Canje de guia transportista
    function indexcanjestr()
    {
        $titulo = "Canjes x Transportista";
        $detalle = [];
        return view('canjestr/index', ['titulo' => $titulo, 'detalle' => $detalle]);
    }
    function listarDetalleCanjeTr(Request $request)
    {
        $guia = new GuiaTransportista();
        $idguia = $request->get('idguia');
        $kardex = $guia->consultarGuiaTrDetalle($idguia);
        foreach ($kardex as $item) {
            // $_SESSION['idautov'] = $item['idautov'];
            // $_SESSION['idautog'] = $item['idautog'];
            // $cguia = $item['idgui'];
            $detalle[] = array(
                'descri' => $item["entr_deta"],
                'unidad' => $item['entr_unid'],
                'cant' => $item['entr_cant'],
                'precio' => 0,
                'subt' => 0,
                'activo' => 'A'
            );
        }
        $_SESSION['idautog'] = $request->get('idguia');
        return view('canjestr/detalle', ['detalle' => $detalle]);
    }
    function registrarcanjetr(Request $request)
    {
        $ovalidar = $this->validar($request, 'O');
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        $venta = new Ventas();
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "iddire" => $request->get("iddire"),
            "tdocv" => $request->get("tdocv"),
            "cndocv" => "",
            "razov" => $request->get('razov'),
            "ndo2v" => $request->get("ndo2v"),
            "txtdireccion" => $request->get("txtdireccion"),
            "txtruccliente" => $request->get("txtruccliente"),
            "txtdnicliente" => $request->get("txtdnicliente"),
            "fechv" => $request->get("fechv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "nidus" => session()->get('usuario_id'),
            "nitem" => 0,
            "detraccion" => $request->get("detraccion")
        );

        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $rpta = $venta->grabarVentaCanjetr($cabecera, $detalle);

        if ($rpta['estado'] == 1) {

            $_SESSION['datosovta'] = $cabecera;
            $_SESSION['detallev'] = $detalle;
            $_SESSION['ndoc'] = $rpta['ndoc'];
            $_SESSION['idautog'] = "";
            return response()->json(['message' => 'Se registro correctamente', 'ndoc' => $rpta['ndoc']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje']], 422);
        }
    }
    function listarvtasnota(Request $request)
    {
        $idCliente = $request->get('idCliente');
        $ventas = new Ventas();
        $listado = $ventas->consultarVentasPorCliente($idCliente);
        return view('notascredito/tm_listavtas', [
            "listado" => $listado
        ]);
    }
    function listardetallenota(Request $request)
    {
        $idauto = $request->get('idauto');
        $tipoventa = $request->get('tipoventa');
        $ventas = new Ventas();
        if ($tipoventa == 'K') {
            $listado = $ventas->consultarDetalleVtaDirecta($idauto);
        } else {
            $listado = $ventas->consultarDetalleVtaServicio($idauto);
        }
        return view('notascredito/detalle', [
            "listado" => $listado
        ]);
    }
    //Validar
    function validar($request, $tipovta = 'V')
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idcliev")->message("Ingrese el cliente a la venta");
        $validar->rule("required", "tdocv");
        if ($tipovta == 'V') {
            $validar->rule("required", "almv");
        }
        $validar->rule("required", "fechv")->message("Fecha de Emisión no es válida");
        $validar->rule("required", "monev");
        $validar->rule("required", "formv");
        $validar->rule("required", "fechvv")->message("Fecha de Vencimiento no es válida");;
        $validar->rule("required", "idvenv");
        $validar->rule("required", "subtotal");
        $validar->rule("required", "igv");
        $validar->rule("required", "total");
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors(), "estado" => 0];
            return $data;
        }
        $datetime1 = date_create($request->get('fechvv'));
        $datetime2 = date_create($request->get('fechv'));
        $interval = date_diff($datetime2, $datetime1);
        if (!fechavalida(date('d/m/Y', strtotime($request->get('fechv'))))) {
            $data = ["errors" => ['Fecha de Emisión no válida'], "estado" => 0];
            return $data;
        }
        if (!fechavalida(date('d/m/Y', strtotime($request->get('fechvv'))))) {
            $data = ["errors" => ['Fecha de Vencimiento no válida'], "estado" => 0];
            return $data;
        }
        $ndias = $interval->days;
        if ($request->get("formv") == 'C') {
            if ($ndias <= 0) {
                $data = ["errors" => ['Es obligatorio que los días de Crédito sean mayor a 0'], "estado" => 0];
                return $data;
            }
            if ($request->get('idcliev') == '2') {
                $data = ["errors" => ['No se puede dar crédito a un cliente generico'], "estado" => 0];
                return $data;
            }
        }
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => ["Sesión vacía"], "estado" => 0];
            return $data;
        }
        if ($tipovta == 'V') {
            if (empty($_SESSION["carritov"])) {
                $data = ["errors" => ['Hubo un error con la conexión a internet, por favor actualice la página'], "estado" => 0];
                return $data;
            }
            if ($_SESSION['config']['validarstock'] == 'S') {
                foreach ($_SESSION['carritov'] as $item) {
                    if ($item['activo'] == 'A') {
                        if (intval($item['cantidad']) > intval($item['stock'])) {
                            $data = ["errors" => ['El producto ' . $item['descripcion'] . ' solo tiene ' . $item['stock']], "estado" => 0];
                            return $data;
                        }
                    }
                }
            }
        }

        $crpta = session()->get("mensajesunat", '');
        if (substr($crpta, 0, 1) == '0') {
            $data = ["errors" => ['Este documento ya fue informado a SUNAT. No es posible actualizar'], "estado" => 0];
            return $data;
        }

        if ($request->get('tdocv') == '03') {
            if ((floatval($request->get('total')) > 700) && (empty($request->get('txtdnicliente')))) {
                $data = ["errors" => ['No se puede registrar la venta, porque el cliente no tiene DNI'], "estado" => 0];
                return $data;
            }
        }

        $data = ["errors" => ["ok"], "estado" => 1];
        return $data;
    }
    function validarDetalleCanje($detalleCanje)
    {
        foreach ($detalleCanje as $d) {
            if (empty($d['precio'])) {
                return false;
            }
        }
        return true;
    }
    function indexvtasxvendedor()
    {
        return view('ventasd/informes/indexvtasxvendedor', ['titulo' => 'Ventas por Vendedor']);
    }
    function listavtasxvendedor(Request $request)
    {
        $ventas = new Ventas();
        $listado = $ventas->mostrarresumenvtasvendedor($request->get('dfechai'), $request->get('dfechaf'), $request->get("nidv"));
        return view('ventasd/informes/listavtasxvendedor', ["listado" => $listado]);
    }
    function indexventadproducto()
    {
        return \view('ventasd/informes/indexlistavdp', ['titulo' => 'Rotación de Productos - Ventas']);
    }
    function listarventadproducto(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $venta = new Ventas();
        $lista = $venta->listarVentasxProducto($dfi, $dff);
        return \view('ventasd/informes/re_lventasdproducto', ['listado' => $lista]);
    }
    function indexlistavxcliente()
    {
        return \view('ventasd/informes/indexlistavxcliente', ['titulo' => 'Informes de Vtas x Cliente']);
    }
    function listavtasxcliente(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $idclie = $request->get('idclie');
        $venta = new Ventas();
        $lista = $venta->listarVentasxCliente($dfi, $dff, $idclie);
        $ventasByNdoc = array();
        $ndoc = '';
        $i = 0;
        foreach ($lista as $l) {
            if ($i == 0) {
                $ndoc = $l['ndoc'];
                $ventasByNdoc[$ndoc][$i] = $l;
                $i = $i + 1;
            } else {
                if ($ndoc == $l['ndoc']) {
                    $ventasByNdoc[$ndoc][$i] = $l;
                    $i = $i + 1;
                } else {
                    $i = 0;
                    $ndoc = $l['ndoc'];
                    $ventasByNdoc[$ndoc][$i] = $l;
                    $i = 1;
                }
            }
        }
        return \view('ventasd/informes/listavtasxcliente', ['listado' => $ventasByNdoc]);
    }
    function indexcanjespedidos()
    {
        $titulo = "Facturar Cotizaciones";
        return view('canjespedidos/index', ['titulo' => $titulo]);
    }
    function registrarcanjepedido(Request $request)
    {
        $ovalidar = $this->validar($request, 'N');
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $venta = new Ventas();
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "tdocv" => $request->get("tdocv"),
            "razov" => $request->get("razov"),
            "txtdireccion" => $request->get("txtdireccion"),
            "txtruccliente" => $request->get("txtruccliente"),
            "txtdnicliente" => $request->get("txtdnicliente"),
            "ndo2v" => $request->get("ndo2v"),
            "almv" => $request->get("almv"),
            "fechv" => $request->get("fechv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "nidus" => session()->get('usuario_id'),
            "nitem" => str_pad(count($detalle), 2, '0', STR_PAD_LEFT),
            'optigv' => $request->get("optigv"),
            "txtreferencia" => $request->get("txtreferencia"),
            "txtefectivo" => 0,
        );

        $_SESSION['carritov'] = $detalle;

        $registro = $venta->grabarVentaGeneral($cabecera);

        $pedido = new Pedido();
        $cambestped = $pedido->cambiarEstado($request->get("idautop"));

        if ($cambestped['estado'] == 0) {
            return response()->json(['message' => 'Error al actualizar estado de pedido', 'error' => $cambestped['mensaje']], 422);
        }

        if ($registro['estado'] == 1) {
            $carritov = session()->get('carritov', []);
            $_SESSION['datosovta'] = $cabecera;
            $_SESSION['detallev'] =  $carritov;
            $_SESSION['ndoc'] = $registro['ndoc'];
            $this->limpiarSesionVtad();
            $rpta = array('mensaje' => "Se Genero la venta ", "ndoc" => $registro['ndoc'], "estado" => '1');
            return json_encode($rpta);
        } else {
            return response()->json(['message' => 'Error al registrar venta', 'error' => $registro['mensaje']], 422);
        }
    }
    function indexlistarvtasresumidas()
    {
        $ctitulo = 'Reporte de Ganancias';
        return view('ventasd/informes/indexlistarvtasutilidades', [
            "titulo" => $ctitulo
        ]);
    }
    function mostrarvtasutilidades(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbAlmacen = $request->get("cmbAlmacen");
        $ventas = new Ventas();
        $listado = $ventas->mostrarventasutilidades($dfi, $dff, $cmbAlmacen);
        return view('ventasd/informes/listarvtasutilidades', [
            "listado" => $listado
        ]);
    }
    function verutilidad(Request $request)
    {
        $usua = $request->get("txtUsuario");
        $pass = $request->get("txtPassword");
        $ousuario = new Usuario();
        $valor = $ousuario->verificarusuarioadministador(trim($usua), $pass);
        if (!empty($valor[0]['idusua'])) {
            $utilidad = CarritoService::verutilidad();
            return response()->json(['message' => Round($utilidad, 2)], 200);
        } else {
            return response()->json(['message' => 'Las credenciales no son correctas'], 422);
        }
    }
    function detailchangedolar(Request $request)
    {
        $tmon = $request->get('moneda');
        if ($tmon == 'D') {
            CarritoService::cambiardetalledolar();
        }
        session()->set('moneda', 'SI');
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ventasd', 'detalle');
        return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function indexlistaventasxano()
    {
        $titulo = "Lista Vtas x Año";
        return view('ventasd/informes/indexlistavtasxano', ['titulo' => $titulo]);
    }
    function listaventasxano(Request $request)
    {
        $venta = new Ventas();
        $rpta = $venta->reporteestadistico($request->get('ano'));
        return view('ventasd/informes/listavtasxano', ['listado' => $rpta]);
    }
    function indexvtasrapidas()
    {
        $this->limpiarsesionventarapida();
        $titulo = "Venta Rápida";
        $serie = \session()->get('cndocv', '');
        $num = \session()->get('numv', '');
        $idventa = \session()->get('idventa', 0);
        session()->set("mensajesunat", "");
        $datosclientev = array();
        // session()->set("vista", "R");
        if (!empty($_SESSION['vista'])) {
            session()->remove('carritov');
            session()->remove('vista');
        }
        return view('ventasrapidas/index', ['titulo' => $titulo, 'datosclientev' => $datosclientev, 'serie' => $serie, 'num' => $num, 'idventa' => $idventa]);
    }
    function listardetallevtarapida()
    {
        $btn = 'Grabar';
        $carritov = \session()->get('carritov', []);
        $total = 0;
        $numero_items = CarritoService::numeroItemsVenta();
        // $txtreferencia = \session()->get('txtreferencia', '');
        return view('ventasrapidas/detalle', ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
    function agregaritemvtarapida(Request $request)
    {
        // $idart = $request->get('txtcodigo');
        // if ($this->verificarsiyaesta($idart)) {
        //     $data = [
        //         'message' => 'Producto ya agregado',
        //         'rpta' => 'N'
        //     ];
        //     return response()->json($data, 422);
        // }
        $stock = $request->get("stock");
        $preciomin = min($request->get("precio1"), $request->get("precio2"), $request->get("precio3"));
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es Obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es Obligatoria');
        $validar->rule("numeric", "txtprecio")->message('El precio debe ser numerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser númerica');
        $validar->rule("min", "txtcantidad", 1)->message('La Cantidad debe de ser mayor a 0');
        if ($_SESSION['config']['validarstock'] == 'S') {
            $validar->rule("max", "txtcantidad", $stock)->message("Stock no disponible");
        }

        // $validar->rule("min", "txtprecio", $preciomin)->message("Precio no permitido");
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
            'tipoproducto' => $request->get('tipoproducto'),
            'costo' => $request->get('costo'),
            'presentaciones' => ($request->get('presentaciones')),
            'presseleccionada' => $request->get('presseleccionada'),
            'cantequi' => $request->get('cantequi'),
            'caant' => 0
        );

        CarritoService::agregarItemVenta($producto, $request->get('cmbmoneda'));
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);

        $carritov = session()->get('carritov', []);
        $cvista = \retornavista('ventasrapidas', 'detalle');
        return view($cvista, [
            'carritov' => $carritov, 'total' => $total, 'items' => $numero_items,
            'carrito' => session()->get("carrito", [])
        ]);
    }
    function soloitemvtarapida(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'unidad' => ($request->get('unidad')),
            'descri' => ($request->get('txtdescri')),
            'cantidad' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtcantidad')),
            'precio' => floatval($request->get('txtprecio') <= 0.00  ? 1 : $request->get('txtprecio')),
            'presseleccionada' => $request->get('presseleccionada'),
            'cantequi' => $request->get('cantequi'),
            'lote' => $request->get('lote'),
            'fechavto' => $request->get('fechavto'),
            'activo' => 'A'
        );
        CarritoService::editarProductoVenta($producto, $request->get('cmbmoneda'));
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function quitaritemvtarapida(Request $request)
    {
        $pos = $request->get('indice');
        CarritoService::quitarItemVenta($pos);
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ventasrapidas', 'detalle');
        return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function limpiarvtarapida()
    {
        $this->limpiarSesionVtad();
        session()->set('moneda', 'S');
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ventasrapidas', 'detalle');
        return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function verificarvalorescarrito(Request $request)
    {
        if (empty($_SESSION['carritov'])) {
            return response()->json([
                'estado' => '1',
                'mensaje' => 'No hay productos en el carrito'
            ], 200);
        }
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        try {
            foreach ($detalle as $d) {
                $producto = array(
                    'id' => $d['id'],
                    'cant' => $d['cant'],
                    'precio' => $d['precio']
                );
                CarritoService::verificarvalorescarrito($producto);
            }
            return response()->json([
                'estado' => '1',
                'mensaje' => 'Se actualizo correctamente'
            ], 200);
        } catch (Exception $exep) {
            return response()->json([
                'estado' => '0',
                'mensaje' => 'No actualizo correctamente'
            ], 422);
        }
    }
}
