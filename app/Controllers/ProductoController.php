<?php

namespace App\Controllers;

use App\Models\Producto;
use App\Services\CarritoService;
use App\Services\Tipodecambio;
use Core\Foundation\Application;
use Valitron\Validator;
use Core\Routing\Controller;
use Core\Http\Request;
use App\Middlewares\AuthAdminMiddleware;
use App\Models\Presentacion;
use Core\Routing\Modelo;

class ProductoController extends Controller
{
    private $producto;
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->producto = new Producto();
    }
    function buscarProductoModal(Request $request)
    {
        $cgr = session()->get('carritogrr', 0);
        if ($cgr != 0) {
            $_SESSION['carritogr'] = $cgr;
        }

        $cgc = session()->get('carritogrc', 0);
        if ($cgc != 0) {
            $_SESSION['carritogc'] = $cgc;
        }

        if (empty($_SESSION['sucursales'])) {
            $modelo = new Modelo();
            $modelo->cargarsucursalesindex();
        }

        $abuscar = $request->get('cbuscar');
        $opt = $request->get('option') == 'nombre' ?  1 : ($request->get('option') == 'codigo' ? 0 : 2);
        $nid = intval($request->get('cbuscar'));
        $nd = Tipodecambio::dtipocambiosistema();
        \session()->set('busquedaPV', $abuscar);
        $lista = $this->producto->BuscarProductos($abuscar, $nd, $opt, $nid);
        \session()->set("listaPV", $lista);
        return view('components/listaproductosmodal', ['lista' => $lista]);
    }
    function index($opt)
    {
        $total = number_format(CarritoService::total(), 2, '.', '');
        $vista = \retornavista('admin/productos', 'index');
        session()->set("tiposel", $opt);
        switch ($opt) {
            case 0:
                $ctitulo = 'Lista P. / Un ITEM';
                break;
            case 1:
                $ctitulo = 'Lista P. / Var. ITEMS';
                break;
            case 3:
                $ctitulo = 'Lista Productos - Combos';
                break;
            case 4:
                $ctitulo = 'Lista Productos';
                return view('admin/productos/indexadmin', ['titulo' => $ctitulo, "totalpedido" => 0]);
                break;
            case 5:
                $ctitulo = 'Ajuste de Inventario';
                break;
        }
        return view($vista, ['titulo' => $ctitulo, "totalpedido" => $total]);
    }
    function buscar(Request $request)
    {
        $abuscar = $request->get('cbuscar');
        $opt = $request->get('option') == 'nombre' ?  1 : ($request->get('option') == 'codigo' ? 0 : 2);
        $nid = intval($request->get('cbuscar'));
        $nd = Tipodecambio::dtipocambiosistema();
        \session()->set('busqueda', $abuscar);
        $lista = $this->producto->BuscarProductos($abuscar, $nd, $opt, $nid);
        $cvista = \retornavista('admin/productos', 're_listaproductos');
        \session()->set("lista", $lista);
        return view($cvista, ['lista' => $lista]);
    }
    function buscaradmin(Request $request)
    {
        $abuscar = $request->get('cbuscar');
        $opt = $request->get('option') == 'nombre' ?  1 : ($request->get('option') == 'codigo' ? 0 : 2);
        $nid = intval($request->get('cbuscar'));
        $nd = Tipodecambio::dtipocambiosistema();
        \session()->set('busqueda', $abuscar);
        $lista = $this->producto->BuscarProductosadmin($abuscar, $nd, $opt, $nid);
        $cvista = \retornavista('admin/productos', 're_listaproductos');
        \session()->set("lista", $lista);
        return view($cvista, ['lista' => $lista]);
    }
    function buscarproductoparacombo(Request $request)
    {
        $abuscar = $request->get('cbuscar');
        $opt = $request->get('option') == 'nombre' ?  1 : ($request->get('option') == 'codigo' ? 0 : 2);
        $nid = intval($request->get('cbuscar'));
        $nd = Tipodecambio::dtipocambiosistema();
        $lista = $this->producto->BuscarProductos($abuscar, $nd, $opt, $nid);
        $cvista = \retornavista('components/', 'listaproductomodalparacombo');
        return view($cvista, ['lista' => $lista]);
    }
    function create()
    {
        $titulo = 'Registrar Producto';
        return view('admin/productos/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function indexregistro()
    {
        $ctitulo = "Registrar Producto";
        $vista = \retornavista('admin/productos', 'vistaproducto');
        return view($vista, ['titulo' => $ctitulo]);
    }
    function registrarProducto(Request $request)
    {
        if (empty($_SESSION['usuario_id'])) {
            $data = ["errors" => ['Sesión vencida, por favor ingrese nuevamente al sistema']];
            return response()->json($data, 422);
        }

        if (!empty($request->get('txtcoda1'))) {
            $exiscodprov = $this->producto->verificarsiexistecodprov($request->get("txtcoda1"));
            if ($exiscodprov['estado'] == '1') {
                $data = ["errors" => ['Código de Proveedor ya registrado previamente']];
                return response()->json($data, 422);
            }
        }
        // $validar = new Validator($request->getBody());
        // $validar->rule("required", "txtdescrip");
        // $validar->rule("required", "txtpeso");
        // if (!$validar->validate()) {
        //     $data = ["errors" => $validar->errors()];
        //     return response()->json($data, 422);
        // }
        // $this->producto->txtdescrip = $request->get("txtdescrip");
        // $this->producto->cmbgrupo = $request->get("cmbgrupo");
        // $this->producto->cmbunidad = $request->get("cmbunidad");
        // $this->producto->cmbcategoria = $request->get("cmbcategoria");
        // $this->producto->cmbmarca = $request->get("cmbmarca");
        // $this->producto->txtStockMin = $request->get("txtStockMin");
        // $this->producto->txtprecio = $request->get("txtprecio");
        // $this->producto->txtpeso = $request->get("txtpeso");

        // $registro = $this->producto->registrarProducto();
        // if ($registro['estado'] == "1") {
        //     return response()->json(['message' => 'Producto registrado correctamente'], 200);
        // } else {
        //     return response()->json(['message' => 'Error al registrar Producto'], 422);
        // };
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtdescrip");
        // $validar->rule("required", "txtStockMin");
        // $validar->rule("required", "txtStockMax");
        // $validar->rule("required", "txtporcpreces");
        // $validar->rule("required", "txtporcprecem");
        // $validar->rule("required", "txtporcprecma");
        $validar->rule("required", "txtcostosig");

        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }

        $this->producto->txtdescrip = trim($request->get('txtdescrip'));
        $estadoexis = $this->producto->verificarsiyaexiste();

        if (count($estadoexis['listado']) > 0) {
            $data = ["errors" => ['El producto ya se encuentra registrado.']];
            return response()->json($data, 422);
        }
        $datos = array(
            "txtcodigo" => $request->get("txtcodigo"),
            "txtdescrip" => $request->get("txtdescrip"),
            "cmbunidad" => $request->get("cmbunidad"),
            "txtcostosig" => empty($request->get("txtcostosig")) ? 0 : $request->get("txtcostosig"),
            "txtcoston" => empty($request->get("txtcoston")) ? 0 : $request->get("txtcoston"),
            "txtpeso" => $request->get("txtpeso"),
            "cmbcategoria" => $request->get("cmbcategoria"),
            "cmbmarca" => $request->get("cmbmarca"),
            "cmbtipp" => $request->get("cmbtipp"),
            "txtcostot" => empty($request->get("txtcostot")) ? 0 : $request->get("txtcostot"),
            "cmbMoneda" => $request->get("cmbMoneda"),
            "txtcomisione" => empty($request->get("txtcomisione")) ? 0 : $request->get("txtcomisione"),
            "txtcomisionc" => empty($request->get("txtcomisionc")) ? 0 : $request->get("txtcomisionc"),
            "txtporcprecma" => empty($request->get("txtporcprecma")) ? 0 : $request->get("txtporcprecma"),
            "txtporcpreces" => empty($request->get("txtporcpreces")) ? 0 : $request->get("txtporcpreces"),
            "txtporcprecem" => empty($request->get("txtporcprecem")) ? 0 : $request->get("txtporcprecem"),
            "txtprecioma" => empty($request->get("txtprecioma")) ? 0 : $request->get("txtprecioma"),
            "txtprecioe" => empty($request->get("txtprecioe")) ? 0 : $request->get("txtprecioe"),
            "txtpreciome" => empty($request->get("txtpreciome")) ? 0 : $request->get("txtpreciome"),
            "txtStockMax" => empty($request->get("txtStockMax")) ? 0 : $request->get("txtStockMax"),
            "txtStockMin" => empty($request->get("txtStockMin")) ? 0 : $request->get("txtStockMin"),
            "dolar" =>  session()->get('gene_dola'),
            "nidusua" => session()->get('usuario_id'),
            "txtcoda1" => empty($request->get("txtcoda1")) ? ' ' : $request->get("txtcoda1"),
            "prod_tigv" => empty($request->get("prod_tigv")) ? 1 : $request->get("prod_tigv")
        );

        if (!empty($_SESSION['config']['ventasexon'])) {
            $rpta = $this->producto->registrarProductoparaselva($datos);
        } else {
            $rpta = $this->producto->registrarProducto($datos);
        }

        if ($rpta['estado']) {
            return response()->json(['message' => 'Producto registrado correctamente', 'idregistro' => $rpta['idregistro']], 200);
        } else {
            return response()->json(['message' => 'Error al registrar producto ' . $rpta['idregistro'], 'idregistro' => '0'], 422);
        };
    }
    function consultarProductoPorID(Request $request)
    {
        // $lista = $this->producto->buscarProductoPorID($id);
        // $i = 0;
        // foreach ($lista as $item) {
        //     if ($i == 0) {
        //         $datosProducto = array(
        //             'idart' => $item['idart'],
        //             'descri' => $item['descri'],
        //             'unid' => $item['unid'],
        //             'prec' => $item['prec'],
        //             'peso' => $item['peso'],
        //             'stockmin' => $item['uno']
        //         );
        //     }
        // }
        // $titulo = 'Actualizar producto ' . $datosProducto['descri'];
        // return view('admin/productos/vistaproducto', [
        //     'titulo' => $titulo, 'datosProducto' => $datosProducto
        // ]);
        $prod_uti1 = round((floatval($request->get('prod_uti1')) * 100) - 100, 6);
        $prod_uti2 = round((floatval($request->get('prod_uti2')) * 100) - 100, 6);
        $prod_uti3 = round((floatval($request->get('prod_uti3')) * 100) - 100, 6);
        $txtcoda1 = str_replace('"', "'", $request->get('txtcoda1'));
        $codigob = str_replace('"', "'", $request->get('codigo'));
        $datosProducto = array(
            'idart' => $request->get('idart'),
            'idcat' => $request->get('idcat'),
            'idmar' => $request->get('idmar'),
            'unid' => $request->get('unid'),
            'idgrupo' => $request->get('idgrupo'),
            'descri' => trim($request->get('descri')),
            'codigo' => empty($codigob) ? ' ' : $codigob,
            'peso' => empty($request->get('peso')) ? 0 : $request->get('peso'),
            "idflete" => $request->get('idflete'),
            "prod_smin" => empty($request->get('prod_smin')) ? 0 : $request->get('prod_smin'),
            "prod_smax" => empty($request->get('prod_smax')) ? 0 : $request->get('prod_smax'),
            "costocigv" => empty($request->get('costocigv')) ? 0 : $request->get('costocigv'),
            "costosigv" => empty($request->get('costosigv')) ? 0 : $request->get('costosigv'),
            "flete" => $request->get("flete"),
            "tmon" => $request->get("tmon"),
            'prod_come' => empty($request->get('prod_come')) ? 0 : $request->get('prod_come'),
            'prod_comc' => empty($request->get('prod_comc')) ? 0 : $request->get('prod_comc'),
            'prod_uti1' => ($prod_uti1 > 0 ? $prod_uti1 : 0),
            'prod_uti2' => ($prod_uti2 > 0 ? $prod_uti2 : 0),
            'prod_uti3' => ($prod_uti3 > 0 ? $prod_uti3 : 0),
            'pre1' => empty($request->get('pre1')) ? 0 : $request->get('pre1'),
            'pre2' => empty($request->get('pre2')) ? 0 : $request->get('pre2'),
            'pre3' => empty($request->get('pre3')) ? 0 : $request->get('pre3'),
            'tipop' => $request->get('tipop'),
            'txtcoda1' => empty($txtcoda1) ? ' ' : $txtcoda1,
            'prod_tigv' => $request->get('prod_tigv')

            // prod_come => comisión efectivo
            // prod_comc=> comisión crédito

            // % Precio mayor => prod_uti1
            // % Precio especial => prod_uti2
            // % Precio menor => prod_uti3

            // Precio mayor => pre1
            // Precio especial => pre2
            // Precio menor => pre3
        );
        $titulo = 'Actualizar Producto';
        return view('admin/productos/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => '', 'datosProducto' => $datosProducto]);
    }
    function actualizar(Request $request)
    {
        if (empty($_SESSION['usuario_id'])) {
            $data = ["errors" => ['Sesión vencida, por favor ingrese nuevamente al sistema']];
            return response()->json($data, 422);
        }

        // $exiscodprov = $this->producto->verificarsiexistecodprovactu($request->get("txtcoda1"));
        // if ($exiscodprov['estado'] == '1') {
        //     $data = ["errors" => ['Código de Proveedor ya registrado previamente']];
        //     return response()->json($data, 422);
        // }

        // $validar = new Validator($request->getBody());
        // $validar->rule("required", "txtdescrip");
        // $validar->rule("required", "txtpeso");

        // if (!$validar->validate()) {
        //     $data = ["errors" => $validar->errors()];
        //     return response()->json($data, 422);
        // }
        // $this->producto->txtidart = $request->get("txtidart");
        // $this->producto->txtdescrip = $request->get("txtdescrip");
        // $this->producto->cmbunidad = $request->get("cmbunidad");
        // $this->producto->txtStockMin = $request->get("txtStockMin");
        // $this->producto->txtprecio = $request->get("txtprecio");
        // $this->producto->txtpeso = floatval($request->get("txtpeso"));

        // $registro = $this->producto->actualizarProducto();
        // if ($registro['estado'] == "1") {
        //     return response()->json(['message' => 'Producto actualizado correctamente'], 200);
        // } else {
        //     return response()->json(['message' => 'Error al registrar Producto'], 422);
        // };

        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtdescrip");
        // $validar->rule("required", "txtStockMin");
        // $validar->rule("required", "txtStockMax");
        // $validar->rule("required", "txtporcpreces");
        $validar->rule("required", "txtporcprecem");
        $validar->rule("required", "txtporcprecma");
        $validar->rule("required", "txtcostosig");

        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        $datos = array(
            "txtcodigo" => $request->get("txtcodigo"),
            "txtdescrip" => $request->get("txtdescrip"),
            "cmbunidad" => $request->get("cmbunidad"),
            "txtcostosig" => empty($request->get("txtcostosig")) ? 0 : $request->get("txtcostosig"),
            "txtcoston" => empty($request->get("txtcoston")) ? 0 : $request->get("txtcoston"),
            "txtpeso" => empty($request->get("txtpeso")) ? 0 : $request->get("txtpeso"),
            "cmbcategoria" => $request->get("cmbcategoria"),
            "cmbmarca" => $request->get("cmbmarca"),
            "cmbtipp" => $request->get("cmbtipp"),
            "txtcostot" => $request->get("txtcostot"),
            "cmbMoneda" => $request->get("cmbMoneda"),
            "txtcomisione" => empty($request->get("txtcomisione")) ? 0 : $request->get("txtcomisione"),
            "txtcomisionc" => empty($request->get("txtcomisionc")) ? 0 : $request->get("txtcomisionc"),
            "txtporcprecma" => empty($request->get("txtporcprecma")) ? 0 : $request->get("txtporcprecma"),
            "txtporcpreces" => empty($request->get("txtporcpreces")) ? 0 : $request->get("txtporcpreces"),
            "txtporcprecem" => empty($request->get("txtporcprecem")) ? 0 : $request->get("txtporcprecem"),
            "txtStockMax" => empty($request->get("txtStockMax")) ? 0 : $request->get("txtStockMax"),
            "txtStockMin" => empty($request->get("txtStockMin")) ? 0 : $request->get("txtStockMin"),
            "dolar" =>  session()->get('gene_dola'),
            "nidusua" => session()->get('usuario_id'),
            "txtprecioma" => empty($request->get("txtprecioma")) ? 0 : $request->get("txtprecioma"),
            "txtprecioe" => empty($request->get("txtprecioe")) ? 0 : $request->get("txtprecioe"),
            "txtpreciome" => empty($request->get("txtpreciome")) ? 0 : $request->get("txtpreciome"),
            "cmbgrupo" => $request->get("cmbgrupo"),
            'idart' => $request->get("idart"),
            'nflete' => $request->get('nflete'),
            'txtcoda1' => empty($request->get('txtcoda1')) ? ' ' : $request->get('txtcoda1'),
            "prod_tigv" => empty($request->get("prod_tigv")) ? 1 : $request->get("prod_tigv")
        );
        if (!empty($_SESSION['config']['ventasexon'])) {
            $rptaactualizar = $this->producto->actualizarProductoparaselva($datos);
        } else {
            $rptaactualizar = $this->producto->actualizarProducto($datos);
        }
        if ($rptaactualizar) {
            return response()->json(['message' => 'Producto actualizado correctamente'], 200);
        } else {
            return response()->json(['message' => 'Error al actualizar Producto'], 422);
        };
    }
    // public function darBaja($id, Request $request)
    // {
    //     try {
    //         if ($this->producto->darBaja($id)) {
    //             return response()->json(['message' => 'Eliminado correctamente'], 200);
    //         } else {
    //             return response()->json(['message' => 'Error al eliminar'], 500);
    //         }
    //     } catch (\Exception $error) {
    //         return response()->json(['message' => 'Error al eliminar'], 500);
    //     }
    // }
    function obtenerPresentacion(Request $request)
    {
        $id = $request->get('id');
        $presentacion = new Presentacion();
        $presentaciones = $presentacion->listar($id, 3.85);
        return response()->json($presentaciones, 200);
    }
    function updateStock(Request $request)
    {
        $correlativo = SerieController::correlativo($_SESSION['nserie'], 'AJ');
        if ($correlativo[0]['estado'] == 0) {
            $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
            return $rpta;
        }
        $idserie = $correlativo[0]['idserie'];
        $cndoc = $correlativo[0]['correlativo'];
        $cabecera = array(
            'ctdoc' => 'AJ',
            'cform' => 'E',
            'cndoc' => $cndoc,
            'dfecha' => date('Y-m-d'),
            'dfechar' => date('Y-m-d'),
            'cdetalle' => 'Ajuste de Inventarios',
            'nv' => '0',
            'nigv' => '0',
            'nt' => '0',
            'cndo2' => '',
            'cm' => 'S',
            'ndolar' => session()->get("gene_dola"),
            'ni' => session()->get("gene_igv"),
            'ctg' => 'K',
            'ccodp' => $_SESSION['config']['idprovajuste'],
            // 'ccodp' => '301', //Este valor es el código del proveedor, se tiene que obtener de la configuración del JSON
            'cmvto' => 'C',
            'nus' => session()->get('usuario_id'),
            'opt' => '0',
            'nidcodt' =>  $_SESSION['idalmacen'],
            'n1' => 0,
            'n2' => 0,
            'n3' => 0,
            'nitem' => 0,
            'npvta' => 0,
            'idserie' => $idserie
        );
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        foreach ($detalle as $d) {
            if ($d['ingreso'] == '') {
                return response()->json(['message' => 'Error al registrar'], 400);
            }
        }
        $rptareg = $this->producto->updateStock($cabecera, $detalle);
        return $rptareg;
    }
    function consultarvtasxprod(Request $request)
    {
        $p = new Producto();
        $p->txtidart = $request->get('txtidart');
        $rpta = $p->consultarvtasxprod($request->get('cmbano'));
        return view('admin/productos/listarinformes', ['listado' => $rpta['listado']]);
    }
    function consultarcompxprod(Request $request)
    {
        $p = new Producto();
        $p->txtidart = $request->get('txtidart');
        $rpta = $p->consultarcompxprod($request->get('cmbano'));
        return view('admin/productos/listarinformes', ['listado' => $rpta['listado']]);
    }
    function consultarlogs(Request $request)
    {
        $p = new Producto();
        $p->txtidart = $request->get('txtidart');
        $rpta = $p->consultarlogs();
        return view('admin/productos/listarlogs', ['listado' => $rpta['listado']]);
    }
    function consultareliminados(Request $request)
    {
        $p = new Producto();
        $rpta = $p->consultareliminados();
        return view('admin/productos/listareliminados', ['listado' => $rpta['listado']]);
    }
    function verdetallecombo(Request $request)
    {
        $p = new Producto();
        $p->txtidart = intval($request->get('txtidart'));
        $rpta = $p->verdetallecombo();
        return view('admin/productos/verdetallecombo', ['listado' => $rpta['listado']]);
    }
    function anularProducto($idart)
    {
        $p = new Producto();
        $rptaestmov = $p->verificarmovimientos($idart);
        if ($rptaestmov['estado'] == '1') {
            return response()->json(['message' => 'No se pudo eliminar, porque tiene transacciones (Compras o Ventas)'], 400);
        }
        $rpta = $p->anularProducto($idart);
        if ($rpta['estado'] == '1') {
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        } else {
            return response()->json(['message' => 'Ocurrió un error'], 400);
        }
    }
    function listarlotesyfechasvto($idart)
    {
        $p = new Producto();
        $rpta = $p->listarlotesyfechasvto($idart);
        return view('components/modallistalotes', ['lista' => $rpta['listado'], 'idart' => $idart]);
    }
}
