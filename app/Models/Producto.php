<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Producto extends Modelo
{
    public int $txtidart = 0;
    public string $txtCodigo = "";
    public int $cmbgrupo = 0;
    public int $cmbcategoria = 0;
    public int $cmbmarca = 0;
    public string $txtdescrip = "";
    public string $cmbunidad = "";
    public float $txtStockMin = 0;
    public float $txtStockMax = 0;
    public float $txtcostosig = 0;
    public float $txtcostocig = 0;
    public float $txtcoston = 0;
    public float $txtcostot = 0;
    public float $txtprecio = 0;
    public int $txtpeso = 0;

    function BuscarProductos($buscar, $nd, $opt, $nid)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $sql = "call promuestraproductos1(:abuscar,:nd,:opt,:nid)";
        $query = $this->prepare($sql);
        try {
            $cbuscar = str_replace(" ", "%", $buscar);
            $query->execute([
                'abuscar' => trim($cbuscar),
                'nd' => $nd,
                'opt' => $opt,
                'nid' => $nid
            ]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $cinco = (isset($row['cin'])) ? $row['cin'] : 0;
                    $seis = (isset($row['sei'])) ? $row['sei'] : 0;
                    $nprecio0 = (isset($row['pre0'])) ? $row['pre0'] : 0;
                    $descri1 = (isset($row['descri1'])) ? $row['descri1'] : '';
                    $ccoda1 = (isset($row['prod_cod1'])) ? $row['prod_cod1'] : '';
                    $cmarca = (isset($row['dmar'])) ? $row['dmar'] : '';
                    // $cpresentacion = (isset($row['prod_deta'])) ? $row['prod_deta'] : '';
                    // $ocant = (isset($row['prod_cant'])) ? $row['prod_cant'] : 0;
                    $item = array(
                        "idart" => $row['idart'],
                        "descri" => $row['descri'],
                        "uno" => $row['uno'],
                        "dos" => $row['dos'],
                        "tre" => $row['tre'],
                        "cua" => $row['cua'],
                        "pre1" => $row['pre1'],
                        "pre2" => $row['pre2'],
                        "pre3" => $row['pre3'],
                        "sei" => $seis,
                        "cin" => $cinco,
                        "pre0" => $nprecio0,
                        "prod_cod1" => $ccoda1,
                        "descri1" => $descri1,
                        "peso" => $row['peso'],
                        "prec" => $row['prec'],
                        "tipro" => $row['tipro'],
                        #datos para registro y modificación de productos
                        "marca" => $cmarca, #marca
                        "idmarca" => $row['idmar'],
                        "idgrupo" => $row['idgrupo'], #grupo
                        "idcat" => $row['idcat'], #linea
                        "unid" => $row['unid'], #unidad
                        "idflete" => $row['idflete'], #flete
                        "prod_smin" => $row['prod_smin'], # stock minimo
                        "prod_smax" => $row['prod_smax'], #stock máximo
                        "costocigv" => $row['costo'], #costo con igv
                        "costo" => $row['costo'],
                        "flete" => $row['flete'], #costo de flete
                        "tmon" => $row['tmon'], #tipo de moneda
                        "prod_come" => $row['prod_come'], #comisión efectivo
                        "prod_comc" => $row['prod_comc'], #comisión crédito
                        "prod_uti1" => $row['prod_uti1'], #% precio mayor
                        "prod_uti2" => $row['prod_uti2'], #% precio especial
                        "prod_uti3" => $row['prod_uti3'], #% precio menor
                        "txtcoda1" => $row['coda1'],
                        "pres_desc" => (isset($row['pres_desc'])) ? $row['pres_desc'] : 'UNID',
                        "epta_prec" => (isset($row['epta_prec'])) ? $row['epta_prec'] : $row['pre1'],
                        "epta_cant" => (isset($row['epta_cant'])) ? $row['epta_cant'] : '1',
                        "epta_idep" => (isset($row['epta_idep'])) ? $row['epta_idep'] : '0',
                        "prod_tigv" => (empty($row['prod_tigv'])) ? 1 : $row['prod_tigv']
                        #falta sacar la formula del costo neto
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e->getMessage()];
        }
        return $data;
    }
    function buscarProductoPorID($id)
    {
        try {
            $sql = "SELECT idart,descri,unid,prec,peso,uno FROM fe_art WHERE idart=:idart";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute(['idart' => $id]);
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function registrarProducto($datos)
    {
        $sql = "SELECT FuncreaProductos(:cdesc,:cunid,:nprec, :ncosto,
        :txtprecioma,:txtprecioe,:txtpreciome,:npeso,:ccat,:cmar,:ctipro,:nflete,:cm,'web',
        :ncome,:ncomc,:nutil1,:nutil2,:nutil3,:nidusua,:nsmax,:nsmin, 
        :ccodigo1,:ndolar,:coda1) AS id";
        try {
            $execsql = $this->prepare($sql);
            $execsql->execute([
                'cdesc' => $datos['txtdescrip'],
                'cunid' => $datos['cmbunidad'],
                'nprec' => $datos['txtcostosig'],
                'ncosto' => $datos['txtcoston'],
                'txtprecioma' => $datos['txtprecioma'],
                'txtprecioe' => $datos['txtprecioe'],
                'txtpreciome' => $datos['txtpreciome'],
                'npeso' => $datos['txtpeso'],
                'ccat' => $datos['cmbcategoria'],
                'cmar' => $datos['cmbmarca'],
                'ctipro' => $datos['cmbtipp'],
                'nflete' => $datos['txtcostot'],
                'cm' => $datos['cmbMoneda'],
                'ncome' => (empty($datos['txtcomisione']) ? 0 : $datos['txtcomisione']),
                'ncomc' => (empty($datos['txtcomisionc']) ? 0 : $datos['txtcomisionc']),
                'nutil1' => (empty($datos['txtporcprecma']) ? 0 : $datos['txtporcprecma']),
                'nutil2' => (empty($datos['txtporcpreces']) ? 0 : $datos['txtporcpreces']),
                'nutil3' => (empty($datos['txtporcprecem']) ? 0 : $datos['txtporcprecem']),
                'nidusua' => $datos['nidusua'],
                'nsmax' => $datos['txtStockMax'],
                'nsmin' => $datos['txtStockMin'],
                'ccodigo1' => $datos['txtcodigo'],
                'ndolar' => $datos['dolar'],
                'coda1' => $datos['txtcoda1']
            ]);
            $rpta = ['estado' => true, 'idregistro' => $execsql->fetchColumn()];
        } catch (PDOException $pdo_error) {
            $rpta = ['estado' => false, 'idregistro' => $pdo_error];
        }
        return $rpta;
    }
    function registrarProductoparaselva($datos)
    {
        $sql = "SELECT FuncreaProductos(:cdesc,:cunid,:nprec, :ncosto,
        :txtprecioma,:txtprecioe,:txtpreciome,:npeso,:ccat,:cmar,:ctipro,:nflete,:cm,'web',
        :ncome,:ncomc,:nutil1,:nutil2,:nutil3,:nidusua,:nsmax,:nsmin, 
        :ccodigo1,:ndolar,:coda1,:prod_tigv) AS id";
        try {
            $execsql = $this->prepare($sql);
            $execsql->execute([
                'cdesc' => $datos['txtdescrip'],
                'cunid' => $datos['cmbunidad'],
                'nprec' => $datos['txtcostosig'],
                'ncosto' => $datos['txtcoston'],
                'txtprecioma' => $datos['txtprecioma'],
                'txtprecioe' => $datos['txtprecioe'],
                'txtpreciome' => $datos['txtpreciome'],
                'npeso' => $datos['txtpeso'],
                'ccat' => $datos['cmbcategoria'],
                'cmar' => $datos['cmbmarca'],
                'ctipro' => $datos['cmbtipp'],
                'nflete' => $datos['txtcostot'],
                'cm' => $datos['cmbMoneda'],
                'ncome' => (empty($datos['txtcomisione']) ? 0 : $datos['txtcomisione']),
                'ncomc' => (empty($datos['txtcomisionc']) ? 0 : $datos['txtcomisionc']),
                'nutil1' => (empty($datos['txtporcprecma']) ? 0 : $datos['txtporcprecma']),
                'nutil2' => (empty($datos['txtporcpreces']) ? 0 : $datos['txtporcpreces']),
                'nutil3' => (empty($datos['txtporcprecem']) ? 0 : $datos['txtporcprecem']),
                'nidusua' => $datos['nidusua'],
                'nsmax' => $datos['txtStockMax'],
                'nsmin' => $datos['txtStockMin'],
                'ccodigo1' => $datos['txtcodigo'],
                'ndolar' => $datos['dolar'],
                'coda1' => $datos['txtcoda1'],
                'prod_tigv' => $datos['prod_tigv']
            ]);
            $rpta = ['estado' => true, 'idregistro' => $execsql->fetchColumn()];
        } catch (PDOException $pdo_error) {
            $rpta = ['estado' => false, 'idregistro' => $pdo_error];
        }
        return $rpta;
    }
    function actualizarProducto($datos)
    {
        // $sql = "update fe_art set descri=:txtdescrip,unid=:cmbunidad,uno=:txtStockMin,peso=:txtpeso,prec=:txtprecio where idart=:txtidart";
        // try {
        //     $query = $this->prepare($sql);
        //     $query->execute([
        //         "txtdescrip" => $this->txtdescrip,
        //         "cmbunidad" => $this->cmbunidad,
        //         "txtStockMin" => $this->txtStockMin,
        //         "txtpeso" => $this->txtpeso,
        //         "txtprecio" => $this->txtprecio,
        //         "txtidart" => $this->txtidart
        //     ]);
        //     $rpta = array('mensaje' => $query->errorInfo(), "estado" => '1');
        //     return $rpta;
        // } catch (PDOException $pdo_error) {
        //     $rpta = array('mensaje' => $query->errorInfo(), "estado" => '0');
        //     return $rpta;
        // }
        $sql = "call ProActualizaProductos(:cdesc,:cunid,:ncosto,
        :txtprecioma,:txtprecioe,:txtpreciome,:npeso,:ccat,:cmar,:ctipro,:nflete,:cm,:nprec,
        :nidgrupo,:nutil1,:nutil2,:nutil3,:ncome,:ncomc,:nidusua,:idart,:nsmax,:nsmin, 
        :ccodigo1,:ndolar,'A',:coda1)";
        try {
            $query = $this->prepare($sql);
            $query->execute([
                'cdesc' => $datos['txtdescrip'],
                'cunid' => $datos['cmbunidad'],
                'ncosto' => $datos['txtcoston'],
                'txtprecioma' => $datos['txtprecioma'],
                'txtprecioe' => $datos['txtprecioe'],
                'txtpreciome' => $datos['txtpreciome'],
                'npeso' => $datos['txtpeso'],
                'ccat' => $datos['cmbcategoria'],
                'cmar' => $datos['cmbmarca'],
                'ctipro' => $datos['cmbtipp'],
                'nflete' => $datos['txtcostot'],
                'cm' => $datos['cmbMoneda'],
                'nprec' => $datos['txtcostosig'],
                "nidgrupo" => $datos['cmbgrupo'],
                'ncome' => $datos['txtcomisione'],
                'ncomc' => $datos['txtcomisionc'],
                'nutil1' => $datos['txtporcprecma'],
                'nutil2' => $datos['txtporcpreces'],
                'nutil3' => $datos['txtporcprecem'],
                'nidusua' => $datos['nidusua'],
                'idart' => $datos['idart'],
                'nsmax' => $datos['txtStockMax'],
                'nsmin' => $datos['txtStockMin'],
                'ccodigo1' => $datos['txtcodigo'],
                'ndolar' => $datos['dolar'],
                'coda1' => $datos['txtcoda1']
            ]);
            return true;
        } catch (PDOException $pdo_error) {
            echo $pdo_error;
            return false;
        }
    }
    function actualizarProductoparaselva($datos)
    {
        // $sql = "update fe_art set descri=:txtdescrip,unid=:cmbunidad,uno=:txtStockMin,peso=:txtpeso,prec=:txtprecio where idart=:txtidart";
        // try {
        //     $query = $this->prepare($sql);
        //     $query->execute([
        //         "txtdescrip" => $this->txtdescrip,
        //         "cmbunidad" => $this->cmbunidad,
        //         "txtStockMin" => $this->txtStockMin,
        //         "txtpeso" => $this->txtpeso,
        //         "txtprecio" => $this->txtprecio,
        //         "txtidart" => $this->txtidart
        //     ]);
        //     $rpta = array('mensaje' => $query->errorInfo(), "estado" => '1');
        //     return $rpta;
        // } catch (PDOException $pdo_error) {
        //     $rpta = array('mensaje' => $query->errorInfo(), "estado" => '0');
        //     return $rpta;
        // }
        $sql = "call ProActualizaProductos(:cdesc,:cunid,:ncosto,
        :txtprecioma,:txtprecioe,:txtpreciome,:npeso,:ccat,:cmar,:ctipro,:nflete,:cm,:nprec,
        :nidgrupo,:nutil1,:nutil2,:nutil3,:ncome,:ncomc,:nidusua,:idart,:nsmax,:nsmin, 
        :ccodigo1,:ndolar,'A',:coda1,:prod_tigv)";
        try {
            $query = $this->prepare($sql);
            $query->execute([
                'cdesc' => $datos['txtdescrip'],
                'cunid' => $datos['cmbunidad'],
                'ncosto' => $datos['txtcoston'],
                'txtprecioma' => $datos['txtprecioma'],
                'txtprecioe' => $datos['txtprecioe'],
                'txtpreciome' => $datos['txtpreciome'],
                'npeso' => $datos['txtpeso'],
                'ccat' => $datos['cmbcategoria'],
                'cmar' => $datos['cmbmarca'],
                'ctipro' => $datos['cmbtipp'],
                'nflete' => $datos['txtcostot'],
                'cm' => $datos['cmbMoneda'],
                'nprec' => $datos['txtcostosig'],
                "nidgrupo" => $datos['cmbgrupo'],
                'ncome' => $datos['txtcomisione'],
                'ncomc' => $datos['txtcomisionc'],
                'nutil1' => $datos['txtporcprecma'],
                'nutil2' => $datos['txtporcpreces'],
                'nutil3' => $datos['txtporcprecem'],
                'nidusua' => $datos['nidusua'],
                'idart' => $datos['idart'],
                'nsmax' => $datos['txtStockMax'],
                'nsmin' => $datos['txtStockMin'],
                'ccodigo1' => $datos['txtcodigo'],
                'ndolar' => $datos['dolar'],
                'coda1' => $datos['txtcoda1'],
                'prod_tigv' => $datos['prod_tigv']
            ]);
            return true;
        } catch (PDOException $pdo_error) {
            echo $pdo_error;
            return false;
        }
    }
    // function registrarProducto()
    // {
    //     $sql = "INSERT INTO fe_art(descri,unid,idmar,idcat,idflete,uno,tipro,peso,prec) VALUES(:txtdescrip,:cmbunidad,:cmbmarca,:cmbcategoria,:idflete,:txtStockMin,'K',:txtpeso,:txtprecio)";
    //     try {
    //         $query = $this->prepare($sql);
    //         $query->execute([
    //             "txtdescrip" => $this->txtdescrip,
    //             "cmbunidad" => $this->cmbunidad,
    //             "cmbmarca" => 1,
    //             "cmbcategoria" => 29,
    //             "idflete" => 1,
    //             "txtStockMin" => $this->txtStockMin,
    //             "txtpeso" => $this->txtpeso,
    //             "txtprecio" => $this->txtprecio
    //         ]);
    //         $rpta = array('mensaje' => $query->errorInfo(), "estado" => '1');
    //         return $rpta;
    //     } catch (PDOException $pdo_error) {
    //         $rpta = array('mensaje' => $query->errorInfo(), "estado" => '0');
    //         return $rpta;
    //     }
    // }
    function updateStock($cabecera, $detalle)
    {
        $data = array();
        $con = new conexion();
        $pdo = $con->conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->beginTransaction();
            $sqlIC = "SELECT FunIngresaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfechar,:cdetalle,:nv,:nigv,:nt,:cndo2,:cm,
                :ndolar,:ni,:ctg,:ccodp,:cmvto,:nus,:opt,:nidcodt,:n1,:n2,:n3,:nitem,:npvta,0) AS id";
            $exeIC = $pdo->prepare($sqlIC);
            $exeIC->execute([
                'ctdoc' => $cabecera['ctdoc'],
                'cform' => $cabecera['cform'],
                'cndoc' => $cabecera['cndoc'],
                'dfecha' => $cabecera['dfecha'],
                'dfechar' => $cabecera['dfechar'],
                'cdetalle' => $cabecera['cdetalle'],
                'nv' => $cabecera['nv'],
                'nigv' => $cabecera['nigv'],
                'nt' => $cabecera['nt'],
                'cndo2' => $cabecera['cndo2'],
                'cm' => $cabecera['cm'],
                'ndolar' => $cabecera['ndolar'],
                'ni' => $cabecera['ni'],
                'ctg' => $cabecera['ctg'],
                'ccodp' => $cabecera['ccodp'],
                'cmvto' => $cabecera['cmvto'],
                'nus' => $cabecera['nus'],
                'opt' => $cabecera['opt'],
                'nidcodt' => $cabecera['nidcodt'],
                'n1' => 0,
                'n2' => 0,
                'n3' => 0,
                'nitem' => $cabecera['nitem'],
                'npvta' => $cabecera['npvta']
            ]);
            $id = $exeIC->fetchColumn();
            foreach ($detalle as $d) {
                // $cant = floatval($d['ingreso']) - floatval($d['stock']);
                $cant = floatval($d['ingreso']) - floatval($d['stock']);
                $sqlIK = "SELECT FunIngresaKardex1(:idauto,:cc,:ctipo,:npr,:nct,:cincl,:tmvto,:ccdov,:calma,:nidcosto1,:vcom,0,'UNIDAD',1,0,'','2000-01-01') AS NID";
                $exeIK = $pdo->prepare($sqlIK);
                $exeIK->execute([
                    'idauto' => $id,
                    'cc' => $d['idart'],
                    'ctipo' => 'C',
                    'npr' => '0',
                    'nct' => $cant,
                    'cincl' => 'I',
                    'tmvto' => 'K',
                    'ccdov' => '0',
                    'calma' => $cabecera['nidcodt'],
                    'nidcosto1' => '0',
                    'vcom' => '0'
                ]);
                $sqlAS = "CALL astock(:coda,:nalma,:ccant,:ctipo,1)";
                $exeAS = $pdo->prepare($sqlAS);
                $exeAS->execute([
                    'coda' => $d['idart'],
                    'nalma' => $cabecera['nidcodt'],
                    'ccant' =>  $cant,
                    'ctipo' => 'C'
                ]);
            }
            Serie::aumentarcorrelativo($cabecera['idserie'], $pdo);
            $pdo->commit();
            $data = ["mensaje" => 'Se ingreso correctamente el documento ' . $cabecera['cndoc'], 'estado' => '1'];
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $data = ["mensaje" => 'Hubieron problemas al ingresar stock ' . $pdo_error->getMessage(), 'estado' => '0'];
        }
        return json_encode($data);
    }
    function consultarTrapaso($ndoc, $tdoc)
    {
        $sql = "SELECT a.ndoc,a.fech,a.mone,b.razo,a.impo AS importe,a.idprov AS codi,idauto,a.idusua AS idusuac FROM
        fe_rcom AS a JOIN fe_prov AS b USING(idprov) WHERE a.ndoc=:ndoc AND tdoc=:tdoc AND a.acti<>'I'";
        $query = $this->prepare($sql);
        $query->execute([
            'ndoc' => $ndoc,
            'tdoc' => $tdoc
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Se obtuvieron los resultados correctamente', 'listado' => $listado, 'estado' => '1'];
        return $data;
    }
    function calcularstockproducto($nidart, $nidalma)
    {
        $sql = "SELECT a.tcompras- a.tventas as stock
        FROM (SELECT b.idart,SUM(IF(b.tipo='C',b.cant,0)) AS tcompras,SUM(IF(b.tipo='V',b.cant,0)) AS tventas,b.alma
        FROM fe_kar AS b WHERE b.acti<>'I' and b.alma=:nalma and b.idart=:nidart GROUP BY  idart) AS a";
        $query = $this->prepare($sql);
        $query->execute([
            "nalma" => $nidalma,
            "nidart" => $nidart
        ]);
        $listado = $query->fetchColumn();
        return $listado;
    }
    function anularTraspaso($id)
    {
        try {
            $sql = "update fe_rcom set acti='I' where idauto=:id";
            $st = $this->prepare($sql);
            $st->execute([
                'id' => $id
            ]);
            $data = ['mensaje' => 'Se elimino correctamente', 'listado' => [], 'estado' => '1'];
        } catch (PDOException $e) {
            $data = ['mensaje' => 'Ocurrió un error' . $e->getMessage(), 'listado' => [], 'estado' => '0'];
        }
        return $data;
    }
    function consultarvtasxprod($ano)
    {
        $sql = "SELECT b.razo,c.fech,cant,prec,c.mone,c.tdoc,c.ndoc,MONTH(c.fech) as mes FROM fe_kar as a INNER JOIN fe_rcom  as c
        ON(c.idauto=a.idauto) 
        inner join fe_clie as b ON (b.idclie=c.idcliente) 
        WHERE idart=:idart AND a.tipo='V' and c.acti<>'I' and a.acti='A' and YEAR(c.fech)=:ano order by c.fech desc;";
        $query = $this->prepare($sql);
        $query->execute([
            'idart' => $this->txtidart,
            'ano' => $ano
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Se obtuvieron los resultados correctamente', 'listado' => $listado, 'estado' => '1'];
        return $data;
    }
    function consultarcompxprod($ano)
    {
        $sql = "SELECT b.razo,c.fech,cant,ROUND(prec*c.vigv,2) as prec,c.mone,c.tdoc,c.ndoc,
		MONTH(c.fech) as mes FROM fe_kar as a
		INNER JOIN fe_rcom  as c ON(c.idauto=a.idauto)
		inner join fe_prov as b ON (b.idprov=c.idprov)
		WHERE idart=:idart and c.acti<>'I' and a.acti='A' and YEAR(c.fech)=:ano order by c.fech desc;";
        $query = $this->prepare($sql);
        $query->execute([
            'idart' => $this->txtidart,
            'ano' => $ano
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Se obtuvieron los resultados correctamente', 'listado' => $listado, 'estado' => '1'];
        return $data;
    }
    function consultarlogs()
    {
        $sql = "SELECT ap.*,u.nomb FROM fe_aproductos ap
                INNER JOIN fe_usua u ON ap.`prod_idus`=u.`idusua`
                WHERE prod_fope>='2025-05-07'and prod_idar=:idart";
        $query = $this->prepare($sql);
        $query->execute([
            'idart' => $this->txtidart
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Se obtuvieron los resultados correctamente', 'listado' => $listado, 'estado' => '1'];
        return $data;
    }
    function consultareliminados()
    {
        $sql = "SELECT ap.*,u.nomb FROM fe_aproductos ap
            INNER JOIN fe_usua u ON ap.`prod_idus`=u.`idusua`
            WHERE prod_fope>='2025-05-07' AND prod_descrinew LIKE '%ANULADO%'";
        $query = $this->prepare($sql);
        $query->execute();
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Se obtuvieron los resultados correctamente', 'listado' => $listado, 'estado' => '1'];
        return $data;
    }
    function verdetallecombo()
    {
        $sql = "SELECT descri,prec,idart FROM fe_combos c
        INNER JOIN fe_art a ON c.`com_idart`=a.`idart`
        WHERE idprodcomb=:idart AND com_acti='A'";
        $query = $this->prepare($sql);
        $query->execute([
            'idart' => $this->txtidart
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Se obtuvieron los resultados correctamente', 'listado' => $listado, 'estado' => '1'];
        return $data;
    }
    function verificarsiyaexiste()
    {
        $sql = "SELECT * from fe_art where descri=:descri and prod_acti='A' limit 1";
        $query = $this->prepare($sql);
        $query->execute([
            'descri' => $this->txtdescrip
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Se obtuvieron los resultados correctamente', 'listado' => $listado, 'estado' => '1'];
        return $data;
    }
    function BuscarProductosadmin($buscar, $nd, $opt, $nid)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $sql = "call promuestraproductos1admin(:abuscar,:nd,:opt,:nid)";
        $query = $this->prepare($sql);
        try {
            $cbuscar = str_replace(" ", "%", $buscar);
            $query->execute([
                'abuscar' => trim($cbuscar),
                'nd' => $nd,
                'opt' => $opt,
                'nid' => $nid
            ]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $cinco = (isset($row['cin'])) ? $row['cin'] : 0;
                    $seis = (isset($row['sei'])) ? $row['sei'] : 0;
                    $nprecio0 = (isset($row['pre0'])) ? $row['pre0'] : 0;
                    $descri1 = (isset($row['descri1'])) ? $row['descri1'] : '';
                    $ccoda1 = (isset($row['prod_cod1'])) ? $row['prod_cod1'] : '';
                    $cmarca = (isset($row['dmar'])) ? $row['dmar'] : '';
                    // $cpresentacion = (isset($row['prod_deta'])) ? $row['prod_deta'] : '';
                    // $ocant = (isset($row['prod_cant'])) ? $row['prod_cant'] : 0;
                    $item = array(
                        "idart" => $row['idart'],
                        "descri" => $row['descri'],
                        "uno" => $row['uno'],
                        "dos" => $row['dos'],
                        "tre" => $row['tre'],
                        "cua" => $row['cua'],
                        "pre1" => $row['pre1'],
                        "pre2" => $row['pre2'],
                        "pre3" => $row['pre3'],
                        "sei" => $seis,
                        "cin" => $cinco,
                        "pre0" => $nprecio0,
                        "prod_cod1" => $ccoda1,
                        "descri1" => $descri1,
                        "peso" => $row['peso'],
                        "prec" => $row['prec'],
                        "tipro" => $row['tipro'],
                        #datos para registro y modificación de productos
                        "marca" => $cmarca, #marca
                        "idmarca" => $row['idmar'],
                        "idgrupo" => $row['idgrupo'], #grupo
                        "idcat" => $row['idcat'], #linea
                        "unid" => $row['unid'], #unidad
                        "idflete" => $row['idflete'], #flete
                        "prod_smin" => $row['prod_smin'], # stock minimo
                        "prod_smax" => $row['prod_smax'], #stock máximo
                        "costocigv" => $row['costo'], #costo con igv
                        "costo" => $row['costo'],
                        "flete" => $row['flete'], #costo de flete
                        "tmon" => $row['tmon'], #tipo de moneda
                        "prod_come" => $row['prod_come'], #comisión efectivo
                        "prod_comc" => $row['prod_comc'], #comisión crédito
                        "prod_uti1" => $row['prod_uti1'], #% precio mayor
                        "prod_uti2" => $row['prod_uti2'], #% precio especial
                        "prod_uti3" => $row['prod_uti3'], #% precio menor
                        "txtcoda1" => $row['coda1'],
                        "pres_desc" => (isset($row['pres_desc'])) ? $row['pres_desc'] : 'UNID',
                        "epta_prec" => (isset($row['epta_prec'])) ? $row['epta_prec'] : $row['pre1'],
                        "epta_cant" => (isset($row['epta_cant'])) ? $row['epta_cant'] : '1',
                        "epta_idep" => (isset($row['epta_idep'])) ? $row['epta_idep'] : '0',
                        "prod_tigv" => (empty($row['prod_tigv'])) ? 1 : $row['prod_tigv']
                        #falta sacar la formula del costo neto
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e->getMessage()];
        }
        return $data;
    }
    function anularProducto($idart)
    {
        try {
            $sql = "update fe_art set prod_acti='I',prod_uact=:idusua,prod_fact=LOCALTIME() where idart=:idart";
            $st = $this->prepare($sql);
            $st->execute([
                'idart' => $idart,
                'idusua' => $_SESSION['usuario_id']
            ]);
            $data = ['mensaje' => 'Se elimino correctamente', 'listado' => [], 'estado' => '1'];
        } catch (PDOException $e) {
            $data = ['mensaje' => 'Ocurrió un error' . $e->getMessage(), 'listado' => [], 'estado' => '0'];
        }
        return $data;
    }
    function verificarmovimientos($idart)
    {
        $sql = "SELECT * FROM fe_kar k 
                INNER JOIN fe_rcom r ON k.`idauto`=r.`idauto`
                WHERE idart=:idart AND k.acti='A' AND r.tdoc IN('01','03')  AND k.alma<>0";
        $st = $this->prepare($sql);
        $st->execute([
            'idart' => $idart
        ]);
        $lista = $st->fetchAll(PDO::FETCH_ASSOC);
        if (count($lista) > 0) {
            $data = ['mensaje' => '', 'listado' => [], 'estado' => '1'];
        } else {
            $data = ['mensaje' => '', 'listado' => [], 'estado' => '0'];
        }
        return $data;
    }
    function listarlotesyfechasvto($idart)
    {
        $sql = "SELECT `t`.`fech_fvto` AS `kar_fvto`,`t`.`fech_lote` AS `kar_lote`,`t`.`fech_idka` AS `fech_idka`,`a`.`alma`AS `alma`,a.cant
                FROM (SELECT idkar,alma,SUM(IF((`tipo` = 'C'),(`cant` * `kar_equi`),-((`cant` * `kar_equi`)))) AS `cant` FROM
                `fe_kar` WHERE acti='A' AND idart=:idart GROUP BY idkar,alma HAVING `cant` > 0 ) AS  `a`
                JOIN `fe_fechas` `t` ON `t`.`fech_idka` = `a`.`idkar`";
        $st = $this->prepare($sql);
        $st->execute([
            'idart' => $idart
        ]);
        $lista = $st->fetchAll(PDO::FETCH_ASSOC);
        if (count($lista) > 0) {
            $data = ['mensaje' => '', 'listado' => $lista, 'estado' => '1'];
        } else {
            $data = ['mensaje' => '', 'listado' => [], 'estado' => '0'];
        }
        return $data;
    }
    function verificarsiexistecodprov($codprov)
    {
        $sql = "SELECT * FROM fe_art WHERE trim(coda1)=trim(:codprov) AND prod_acti='A'";
        $st = $this->prepare($sql);
        $st->execute([
            'codprov' => $codprov
        ]);
        $lista = $st->fetchAll(PDO::FETCH_ASSOC);
        if (count($lista) > 0) {
            $data = ['mensaje' => '', 'listado' => $lista, 'estado' => '1'];
        } else {
            $data = ['mensaje' => '', 'listado' => [], 'estado' => '0'];
        }
        return $data;
    }
    function verificarsiexistecodprovactu($codprov)
    {
        $sql = "SELECT * FROM fe_art WHERE trim(coda1)=trim(:codprov) AND prod_acti='A'";
        $st = $this->prepare($sql);
        $st->execute([
            'codprov' => $codprov
        ]);
        $lista = $st->fetchAll(PDO::FETCH_ASSOC);
        if (count($lista) <= 1) {
            $data = ['mensaje' => '', 'listado' => $lista, 'estado' => '1'];
        } else {
            $data = ['mensaje' => '', 'listado' => [], 'estado' => '0'];
        }
        return $data;
    }
}
