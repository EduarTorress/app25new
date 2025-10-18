<?php

namespace App\Models;

use App\Controllers\SerieController;
use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Ventas extends Modelo
{
    public $fechv = "";
    public $fechvv = "";
    public $n1 = 0;
    public $n2 = 0;
    public $n3 = 0;
    public $cndoc = "";

    function mostrarventas($dfi, $dff, $tipovta, $cmbFormaP, $cmbmoneda, $cmbtdoc, $cmbAlmacen)
    {
        try {
            $t = ($tipovta == '0') ? ' and tcom<>:tipovta' : ' and tcom=:tipovta ';
            $f = ($cmbFormaP == '0') ? ' and form<>:cmbFormaP  ' : ' and form=:cmbFormaP ';
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
            $m = ' and mone=:cmbmoneda ';
            $tc = ($cmbtdoc == '0') ? ' and tdoc<>:cmbtdoc' : ' and tdoc=:cmbtdoc ';
            $sql = "select ndoc as dcto,a.fech,b.nruc,b.razo,if(a.mone='S','Soles','Dólares') as mone,
                a.valor,a.rcom_exon,CAST(0 as decimal(12,2)) as inafecto,fusua,
                a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,tdoc,
                CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml
                FROM fe_rcom as a 
                inner JOIN fe_clie as b ON (a.idcliente=b.idclie),fe_gene as v
                where a.fech between :dfi and :dff and a.acti='A' and tdoc<>'09'" . $t . $f . $m . $a . $tc . " order by fusua,fech,ndoc";
            $query = $this->prepare($sql);
            // print($query->debugDumpParams());
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'tipovta' => $tipovta,
                'cmbFormaP' => $cmbFormaP,
                'cmbmoneda' => $cmbmoneda,
                'cmbtdoc' => $cmbtdoc,
                'cmbAlmacen' => $cmbAlmacen
            ]);
            $lista = $query->fetchAll(PDO::FETCH_ASSOC);
            return $lista;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
    function mostrarvtasanuladas($dfi, $dff, $cmbtdoc, $cmbAlmacen)
    {
        try {
            $tc = ($cmbtdoc == '0') ? ' and tdoc<>:cmbtdoc' : ' and tdoc=:cmbtdoc ';
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
            $sql = "select ndoc as dcto,a.fech,b.nruc,b.razo,if(a.mone='S','Soles','Dólares') as mone,
                a.valor,a.rcom_exon,CAST(0 as decimal(12,2)) as inafecto,fusua,
                a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,tdoc,u.nomb as usuario,
                CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml
                FROM fe_rcom as a 
                inner JOIN fe_clie as b ON (a.idcliente=b.idclie)
                inner join fe_usua as u on (a.idusua=u.idusua),fe_gene as v
                where a.fech between :dfi and :dff and a.acti='A' and tdoc<>'09' and idcliente=1" . $tc . $a . " order by fech,ndoc";
            $query = $this->prepare($sql);
            // print($query->debugDumpParams());
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'cmbtdoc' => $cmbtdoc,
                'cmbAlmacen' => $cmbAlmacen
            ]);
            $lista = $query->fetchAll(PDO::FETCH_ASSOC);
            return $lista;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
    function reporteestadistico($año)
    {
        $sql = "SELECT q.mes,SUM(q.sucu1) AS '1',SUM(q.sucu2) AS '2',SUM(q.sucu3) AS '3',SUM(q.sucu1+q.sucu2+q.sucu3) AS tot FROM ( SELECT w.mes,
        SUM(CASE w.codt WHEN 1 THEN w.impo ELSE 0 END) AS sucu1,
        SUM(CASE w.codt WHEN 2 THEN w.impo ELSE 0 END) AS sucu2,
        SUM(CASE w.codt WHEN 3 THEN w.impo ELSE 0 END) AS sucu3
        FROM (SELECT MONTH(a.fech) AS mes,YEAR(a.fech) AS año,a.form,IF(a.mone='S',a.impo,a.impo*a.dolar) AS impo,a.codt FROM fe_rcom AS a
        INNER JOIN fe_clie AS b ON b.idclie=a.idcliente
        WHERE a.acti='A' AND YEAR(fech)=:ano  AND rcom_entr='P' ORDER BY fech) AS w  GROUP BY mes,codt) AS q GROUP BY mes";
        $query = $this->prepare($sql);
        $query->execute([
            'ano' => $año
        ]);
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    function mostraroventas($dfi, $dff, $nidt)
    {
        try {
            if ($nidt === 0) {
                $sql = "select ndoc as dcto,a.fech,b.nruc,b.razo,if(a.mone='S','Soles','Dólares') as mone,
                a.valor,a.rcom_exon,CAST(0 as decimal(12,2)) as inafecto,
                a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,
                CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml
                FROM fe_rcom as a 
                inner JOIN fe_clie as b ON (a.idcliente=b.idclie),fe_gene as v
                where a.fech between :dfi and :dff  and a.acti='A' AND impo<>0 and tcom='T' order by fech,ndoc";
                $query = $this->prepare($sql);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $query->execute([
                    'dfi' => $dfi,
                    'dff' => $dff
                ]);
            } else {
                $sql = "select ndoc as dcto,a.fech,b.nruc,b.razo,if(a.mone='S','Soles','Dólares') as mone,
                a.valor,a.rcom_exon,CAST(0 as decimal(12,2)) as inafecto,
                a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,
                CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml
                FROM fe_rcom as a 
                inner JOIN fe_clie as b ON (a.idcliente=b.idclie),fe_gene as v
                where a.fech between :dfi and :dff  and a.acti='A' and codt=:nidt and tcom='T' AND impo<>0 order by fech,ndoc";
                // $db=new conexion();
                $query = $this->prepare($sql);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $query->execute([
                    'dfi' => $dfi,
                    'dff' => $dff,
                    'nidt' => $nidt
                ]);
            }
            return $query;
        } catch (PDOException $e) {
            echo ('Error al consultar ' . $e->getMessage());
        }
    }
    function mostrarventasdetalladas($dfi, $dff, $nidt)
    {
        try {
            if ($nidt === 0) {
                $sql = "select  a.tdoc,a.ndoc,a.fech,c.razo as cliente,d.descri as producto,d.unid,e.cant,e.prec,a.mone,f.nomb as usuario,prod_cod1 as codigofabrica,
			    ifnull(l.dcat,'') as categoria,ifnull(desgrupo,'') as grupo,ifnull(m.dmar,'') as marca,
			    ifnull(prod_acti,'') as estado,round(if(d.tmon='S',(d.prec*z.igv)+b.prec,(d.prec*z.igv*z.dola)+b.prec),2) as costo,
			    e.cant*e.prec  as impo,a.form,a.fusua as hora,c.nruc,c.ndni,d.idart  fROM
				fe_rcom as a 
				inner join fe_clie as c on c.idclie=a.idcliente
				inner join fe_kar as e on e.idauto=a.idauto
				inner join fe_art as d on d.idart=e.idart
				left join fe_cat as l on l.idcat=d.idcat
				left join fe_grupo as g on g.idgrupo=l.idgrupo
				left  join fe_mar as m on m.idmar=d.idmar
				left join fe_fletes as b on b.idflete=d.idflete
				inner join fe_usua as f on f.idusua=a.idusua,fe_gene as z
				where a.fech between :dfi AND :dff and a.acti='A' and e.acti='A' order by a.fech";
            } else {
                $sql = "select  a.tdoc,a.ndoc,a.fech,c.razo as cliente,d.descri as producto,d.unid,e.cant,e.prec,a.mone,f.nomb as usuario,prod_cod1 as codigofabrica,
			    ifnull(l.dcat,'') as categoria,ifnull(desgrupo,'') as grupo,ifnull(m.dmar,'') as marca,
			    ifnull(prod_acti,'') as estado,round(if(d.tmon='S',(d.prec*z.igv)+b.prec,(d.prec*z.igv*z.dola)+b.prec),2) as costo,
			    e.cant*e.prec  as impo,a.form,c.nruc,c.ndni,d.idart,a.fusua as hora  fROM
				fe_rcom as a 
				inner join fe_clie as c on c.idclie=a.idcliente
				inner join fe_kar as e on e.idauto=a.idauto
				inner join fe_art as d on d.idart=e.idart
				left join fe_cat as l on l.idcat=d.idcat
				left join fe_grupo as g on g.idgrupo=l.idgrupo
				left  join fe_mar as m on m.idmar=d.idmar
				left join fe_fletes as b on b.idflete=d.idflete
				inner join fe_usua as f on f.idusua=a.idusua,fe_gene as z
				where a.fech between :dfi AND :dff and a.acti='A' and e.acti='A' and a.codt=:nidt order by a.fech";
            }
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            if ($nidt === 0) {
                $query->execute([
                    'dfi' => $dfi,
                    'dff' => $dff
                ]);
            } else {
                $query->execute([
                    'dfi' => $dfi,
                    'dff' => $dff,
                    'nidt' => $nidt
                ]);
            }
            return $query;
        } catch (PDOException $e) {
            echo ('Error al consultar ' . $e->getMessage());
        }
    }
    public function consultardcto($nidauto, $ctipovta)
    {
        if ($ctipovta === 'S' or $ctipovta === 'T') {
            $consulta = "SELECT  r.idauto,r.ndoc,r.tdoc,r.fech AS dfecha,IF(r.mone='S','PEN','USD') AS mone,valor,
            cast(0 as decimal(12,2)) as  inafectas,CAST(0 AS DECIMAL(12,2)) AS gratificaciones,r.mone AS moneda,
            CAST(0 AS DECIMAL(12,2)) AS exoneradas,'10' AS tigv,vigv,v.rucfirmad,v.razonfirmad,ndo2,
            v.nruc AS rucempresa,v.empresa,v.ubigeo,r.mone AS moneda,
            v.ptop,v.ciudad,v.distrito,c.nruc,IF(tdoc='01','6','1') AS tipodoccliente,c.razo,
            CONCAT(TRIM(c.dire)) AS direccion,c.ndni,rcom_otro,CAST(0 AS DECIMAL(10,2)) AS costoref,deta,
            'PE' AS pais,r.igv,CAST(0 AS DECIMAL(12,2)) AS tdscto,CAST(0 AS DECIMAL(12,2)) AS Tisc,
            impo,CAST(0 AS DECIMAL(12,2)) AS montoper,'I' AS incl,
            CAST(0 AS DECIMAL(12,2)) AS totalpercepcion,IFNULL(k.detv_cant,1) AS cant,IFNULL(k.detv_prec,r.impo) AS prec,
            LEFT(r.ndoc,4) AS serie,SUBSTR(r.ndoc,5) AS numero,detv_desc AS descri,detv_idvt AS  coda,
            'NIU' AS unid,'ZZ' AS unid1,s.codigoestab,r.form,v.gene_usol,v.gene_csol,'PE' AS pais,'OFICINA' AS vendedor,
            v.gene_cert,v.clavecertificado,ifnull(p.fevto,r.fech) as fvto,IF(rcom_detr='',0,rcom_detr) AS rcom_detr
            FROM fe_rcom r
            INNER JOIN fe_clie c ON c.idclie=r.idcliente
            INNER JOIN fe_detallevta k ON k.detv_idau=r.idauto
            INNER JOIN fe_sucu s ON s.idalma=r.codt
            left join (select rcre_idau,min(c.fevto) as fevto from fe_rcred as r inner join fe_cred as c on c.cred_idrc=r.rcre_idrc
            where rcre_acti='A' and acti='A' and rcre_idau=:nidauto group by rcre_idau) as p on p.rcre_idau=r.idauto,fe_gene AS v
            WHERE r.idauto=:nidauto AND r.acti='A' AND detv_item>0 AND detv_acti='A'";
        } else {
            $consulta = "SELECT r.idauto,r.ndoc,r.tdoc,r.fech AS dfecha,IF(r.mone='S','PEN','USD') AS mone,valor,rcom_vuelto,
            CAST(0 AS DECIMAL(12,2)) AS inafectas,CAST(0 AS DECIMAL(12,2)) AS gratificaciones,r.mone AS moneda,
            CAST(0 AS DECIMAL(12,2)) AS exoneradas,'10' AS tigv,vigv,v.rucfirmad,v.razonfirmad,ndo2,
            v.nruc AS rucempresa,v.empresa,v.ubigeo,r.mone AS moneda, v.ptop,v.ciudad,v.distrito,fusua,
            c.nruc,IF(tdoc='01','6','1') AS tipodoccliente,c.razo, CONCAT(TRIM(c.dire)) AS direccion,
            c.ndni,rcom_otro,kar_cost AS costoref,deta, 'PE' AS pais,r.igv,CAST(0 AS DECIMAL(12,2)) AS tdscto,
            CAST(0 AS DECIMAL(12,2)) AS Tisc, impo,CAST(0 AS DECIMAL(12,2)) AS montoper,k.incl,p.nomv AS vendedor,
            CAST(0 AS DECIMAL(12,2)) AS totalpercepcion,k.cant,k.prec,LEFT(r.ndoc,4) AS serie, SUBSTR(r.ndoc,5) AS numero,
            k.kar_unid as unid,a.descri,k.idart AS coda, IFNULL(unid_codu,'NIU')AS unid1,s.codigoestab,r.form,v.gene_usol,v.gene_csol,
            'PE' AS pais, v.gene_cert,v.clavecertificado,IFNULL(p.fevto,r.fech) AS fvto,k.incl 
            FROM fe_rcom r INNER JOIN fe_clie c ON c.idclie=r.idcliente 
            INNER JOIN fe_kar k ON k.idauto=r.idauto 
            LEFT JOIN `fe_vend` `p`  ON ((`p`.`idven` = `k`.`codv`))
            INNER JOIN fe_art a ON a.idart=k.idart 
            INNER JOIN fe_sucu s ON s.idalma=r.codt 
            LEFT JOIN fe_unidades AS u ON u.unid_codu=a.unid 
            LEFT JOIN (SELECT rcre_idau,MIN(c.fevto) AS fevto FROM fe_rcred AS r INNER JOIN fe_cred AS c ON c.cred_idrc=r.rcre_idrc WHERE rcre_acti='A' AND acti='A' AND rcre_idau=:nidauto GROUP BY rcre_idau) AS p ON p.rcre_idau=r.idauto, 
            fe_gene AS v WHERE r.idauto=:nidauto AND r.acti='A' AND k.acti='A' order by k.idkar";
        }
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($consulta);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $st->execute(["nidauto" => $nidauto]);
        return $st;
    }
    function grabaroVentaGeneral($cabecera, $detalle)
    {
        try {
            $correlativo = SerieController::correlativo($_SESSION['nserie'], $cabecera["tdocv"]);
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se puede obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];
            $ls = "SELECT FuningresaDocumentoElectronico(
                :tdocv,:formv,:cndocv,:fechv,'web', :subtotal,:igv,:total,:ndo2v,:monev,
                :dola,:vigv,'T',:idcliev,'V',:nidus,:almv,:n1,:n2,:n3,'027','0.00','0.00','0.00') AS ID";
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($ls);
            $st->execute([
                'tdocv' => $cabecera["tdocv"],
                'formv' => $cabecera["formv"],
                'cndocv' => $this->cndoc,
                'fechv' => $cabecera["fechv"],
                'subtotal' => $cabecera["subtotal"],
                'igv' => $cabecera["igv"],
                'total' => $cabecera["total"],
                'ndo2v' => $cabecera["ndo2v"],
                'monev' => $cabecera['monev'],
                'dola' => session()->get("gene_dola"),
                'vigv' => session()->get("gene_igv"),
                'nidus' => $cabecera["nidus"],
                'idcliev' => $cabecera["idcliev"],
                'almv' => $cabecera["almv"],
                'n1' => session()->get("gene_idctav"),
                'n2' => session()->get("gene_idctai"),
                'n3' => session()->get("gene_idctat"),
                // 'txtdetraccion' => $cabecera["txtdetraccion"]
            ]);
            if ($st->errorCode() != '00000') {
                $pdo->rollBack();
                // $st->debugDumpParams();
                // print_r($st->errorCode());
                $rpta = array('mensaje' => $st->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $id = $st->fetchColumn();

            $sql = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,'',:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $query = $pdo->prepare($sql);
            $query->execute([
                'fechv' =>  $this->fechv,
                'cndocv' => $this->cndoc,
                'n3' => session()->get('gene_idctat'),
                'total' => $cabecera["total"],
                'nidus' => $cabecera["nidus"],
                'nidauto' => $id,
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $cabecera["almv"]
            ]);

            // $query->debugDumpParams();

            if ($query->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,1,'web') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $id,
                    "nid" => $cabecera["idcliev"],
                    "cndoc" => $this->cndoc,
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $cabecera["fechvv"],
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $this->cndoc,
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => $cabecera['idvenv'],
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"]
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    $rpta = array('mensaje' => $stcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            $sql1 = "CALL ProIngresaDetalleVta(:cdesc,:nitem,'0','0',:nid,:nprecio,:ncant,:cunid)";
            $i = 0;
            $sw = 1;
            foreach ($detalle as $item) {
                $query1 = $pdo->prepare($sql1);
                $i++;
                $desc = $item['descripcion'];
                $cant = floatval($item['cantidad']);
                $unidad = $item['unidad'];
                $prec = floatval($item['precio']);

                $query1->execute([
                    "cdesc" => $desc,
                    "nitem" => $i,
                    "nid" => $id,
                    "nprecio" => $prec,
                    "ncant" => $cant,
                    "cunid" => $unidad
                ]);
                //  $query1->debugDumpParams();
                if ($query1->errorCode() != '00000') {
                    $sw = 0;
                    break;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query1->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => "Al actualizar correlativo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }

    function buscarOVentaPorId($idauto)
    {
        $sql = "select `c`.`rcom_icbper` AS `rcom_icbper`, `a`.`kar_icbper`  AS `kar_icbper`, `c`.`rcom_mens`   AS `rcom_mens`,IFNULL(m.fevto,c.fech) AS fvto,
                `c`.`idusua`      AS `idusua`, `a`.`kar_comi`    AS `kar_comi`, `a`.`codv`        AS `codv`,`a`.`idauto`      AS `idauto`,
                `a`.`alma`        AS `alma`, `a`.`kar_idco`    AS `idcosto`, `a`.`idkar`       AS `idkar`, `a`.`idart`       AS `Coda`,
                `a`.`cant`        AS `cant`, `a`.`prec`        AS `prec`, `c`.`valor`       AS `valor`, `c`.`igv`         AS `igv`,
                `c`.`impo`        AS `impo`, `c`.`fech`        AS `fech`, `c`.`fecr`        AS `fecr`, `c`.`form`        AS `form`,
                `c`.`deta`        AS `deta`, `c`.`exon`        AS `exon`, `c`.`ndo2`        AS `ndo2`, `c`.`rcom_entr`   AS `rcom_entr`,
                `c`.`idcliente`   AS `idclie`, `d`.`razo`        AS `razo`, `d`.`nruc`        AS `nruc`, `d`.`dire`        AS `dire`,
                `d`.`ciud`        AS `ciud`,  `d`.`ndni`        AS `ndni`, `a`.`tipo`        AS `tipo`, `c`.`tdoc`        AS `tdoc`,
                `c`.`ndoc`        AS `ndoc`, `c`.`dolar`       AS `dolar`, `c`.`mone`        AS `mone`,  `b`.`descri`      AS `descri`,
                `b`.`unid`        AS `unid`, `b`.`pre1`        AS `pre1`, `b`.`peso`        AS `peso`, `b`.`pre2`        AS `pre2`,
                `c`.`vigv`        AS `vigv`, `a`.`dsnc`        AS `dsnc`, `a`.`dsnd`        AS `dsnd`, `a`.`gast`        AS `gast`,
                `c`.`idcliente`   AS `idcliente`, `c`.`codt`        AS `codt`, `b`.`pre3`        AS `pre3`, `b`.`cost`        AS `costo`,
                `b`.`uno`         AS `uno`, `b`.`dos`         AS `dos`, (`b`.`uno` + `b`.`dos`) AS `TAlma`, `c`.`fusua`       AS `fusua`,
                `p`.`nomv`        AS `Vendedor`, `q`.`nomb`        AS `Usuario`, `c`.`rcom_idtr`   AS `rcom_idtr`, `c`.`rcom_tipo`   AS `rcom_tipo`,c.rcom_detr
                FROM `fe_rcom` `c`
                JOIN `fe_kar` `a`  ON ((`a`.`idauto` = `c`.`idauto`))
                JOIN `vlistaprecios` `b`  ON ((`b`.`idart` = `a`.`idart`))
                JOIN `fe_clie` `d`  ON ((`d`.`idclie` = `c`.`idcliente`))
                LEFT JOIN `fe_vend` `p`  ON ((`p`.`idven` = `a`.`codv`))
                JOIN `fe_usua` `q`  ON ((`q`.`idusua` = `c`.`idusua`))
                LEFT JOIN (SELECT rcre_idau,MIN(c.fevto) AS fevto FROM fe_rcred AS r INNER JOIN fe_cred AS c ON c.cred_idrc=r.rcre_idrc
                WHERE rcre_acti='A' AND acti='A' AND rcre_idau=:nidauto GROUP BY rcre_idau) AS m ON m.rcre_idau=c.idauto
                WHERE `c`.`acti` <> 'I'  AND `a`.`acti` <> 'I' and a.idauto=:nidauto";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'nidauto' => $idauto
        ]);
        return $query;
    }

    function buscarVentaPorId($idauto)
    {
        $sql = "select `c`.`rcom_icbper` AS `rcom_icbper`, `a`.`kar_icbper`  AS `kar_icbper`, `c`.`rcom_mens`   AS `rcom_mens`,IFNULL(m.fevto,c.fech) AS fvto,
                `c`.`idusua`, `a`.`kar_comi`    AS `kar_comi`, `a`.`codv`        AS `codv`,`a`.`idauto`      AS `idauto`,
                `a`.`alma` , `a`.`kar_idco`    AS `idcosto`, `a`.`idkar`       AS `idkar`, `a`.`idart`       AS `Coda`,
                `a`.`cant`        AS `cant`, `a`.`prec`        AS `prec`, `c`.`valor`       AS `valor`, `c`.`igv`         AS `igv`,
                `c`.`impo`        AS `impo`, `c`.`fech`        AS `fech`, `c`.`fecr`        AS `fecr`, `c`.`form`        AS `form`,
                `c`.`deta`        AS `deta`, `c`.`exon`        AS `exon`, `c`.`ndo2`        AS `ndo2`, `c`.`rcom_entr`   AS `rcom_entr`,
                `c`.`idcliente`   AS `idclie`, `d`.`razo`        AS `razo`, `d`.`nruc`        AS `nruc`, `d`.`dire`        AS `dire`,
                `d`.`ciud`        AS `ciud`,  `d`.`ndni`        AS `ndni`, `a`.`tipo`        AS `tipo`, `c`.`tdoc`        AS `tdoc`,
                `c`.`ndoc`        AS `ndoc`, `c`.`dolar`       AS `dolar`, `c`.`mone`        AS `mone`,  `b`.`descri`      AS `descri`,
                `a`.`kar_unid`        AS `unid`, `b`.`pre1`        AS `pre1`, `b`.`peso`        AS `peso`, `b`.`pre2`        AS `pre2`,
                `c`.`vigv`        AS `vigv`, `a`.`dsnc`        AS `dsnc`, `a`.`dsnd`        AS `dsnd`, `a`.`gast`        AS `gast`,
                `c`.`idcliente`   AS `idcliente`, `c`.`codt`        AS `codt`, `b`.`pre3`        AS `pre3`, `b`.`cost`        AS `costo`,
                `b`.`uno`         AS `uno`, `b`.`dos`         AS `dos`, (`b`.`uno` + `b`.`dos`) AS `TAlma`, `c`.`fusua`       AS `fusua`,b.tipro,
                `p`.`nomv`        AS `vendedor`, `q`.`nomb`        AS `Usuario`, `c`.`rcom_idtr`   AS `rcom_idtr`, `c`.`rcom_tipo`   AS `rcom_tipo`,a.`incl`,
                a.kar_epta,a.kar_equi,a.kar_lote,a.kar_fvto,
                pe.pres_desc,e.epta_prec,epta_cant,epta_idep
                FROM `fe_rcom` `c`
                JOIN `fe_kar` `a`  ON ((`a`.`idauto` = `c`.`idauto`))
                JOIN `vlistaprecios` `b`  ON ((`b`.`idart` = `a`.`idart`))
                JOIN `fe_clie` `d`  ON ((`d`.`idclie` = `c`.`idcliente`))
                LEFT JOIN `fe_vend` `p`  ON ((`p`.`idven` = `a`.`codv`))
                LEFT JOIN fe_epta e ON (a.idart=e.epta_idar)
		        LEFT JOIN `fe_presentaciones` pe ON (e.epta_pres=pe.pres_idpr)
                JOIN `fe_usua` `q`  ON ((`q`.`idusua` = `c`.`idusua`))
                LEFT JOIN (SELECT rcre_idau,MIN(c.fevto) AS fevto FROM fe_rcred AS r INNER JOIN fe_cred AS c ON c.cred_idrc=r.rcre_idrc
                WHERE rcre_acti='A' AND acti='A' AND rcre_idau=:nidauto GROUP BY rcre_idau) AS m ON m.rcre_idau=c.idauto
                WHERE `c`.`acti` <> 'I'  AND `a`.`acti` <> 'I' and epta_acti<>'I' and a.idauto=:nidauto order by idkar";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'nidauto' => $idauto
        ]);
        return $query;
    }

    function mostrsroventas($idauto)
    {
        $ls = "SELECT  b.nruc,b.razo,b.dire,b.ciud,a.dolar,a.fech,a.fecr,a.mone,a.idauto,a.vigv,a.valor,a.igv,
        a.impo,ndoc,a.deta,a.tcom,a.idcliente as idclie,a.ndo2,4 as codv,1 as alma, 
        w.impo AS impo1,c.nomb,w.nitem,c.ncta,w.tipo,a.form,rcom_detr,rcom_mdet,
        w.idectas,w.idcta,a.rcom_dsct,rcom_idtr,rcom_mens,IFNULL(p.fevto,a.fech) AS fvto,
        tdoc
        FROM fe_rcom AS a
        INNER JOIN fe_ectas AS w ON w.idrven=a.idauto
        INNER JOIN fe_plan AS c ON c.idcta=w.idcta
        INNER JOIN fe_clie AS b ON b.idclie=a.idcliente
        LEFT JOIN (SELECT rcre_idau,MIN(c.fevto) AS fevto FROM fe_rcred AS r INNER JOIN fe_cred AS c ON c.cred_idrc=r.rcre_idrc
        WHERE rcre_acti='A' AND acti='A' AND rcre_idau=:nidauto GROUP BY rcre_idau) AS p ON p.rcre_idau=a.idauto
        WHERE a.idauto=:nidauto AND w.acti='A' ";
        $query = $this->prepare($ls);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'nidauto' => $idauto
        ]);
        return $query;
    }

    function mostrardetalloventas($idauto)
    {
        $sql = "select q.detv_desc AS descri,q.detv_item,q.detv_ite1,q.detv_ite2,detv_prec AS prec,
        detv_cant AS cant,detv_unid as unidad,detv_idvt AS nreg from fe_detallevta AS q where q.detv_idau=:nidauto AND detv_acti='A'";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'nidauto' => $idauto
        ]);
        return $query;
    }

    function actualizarOVenta($cabecera, $detalle)
    {
        $ls = "CALL ProActualizaCabeceraVentascdetraccion(:ctdoc,:cform,:cndoc,:dfecha,:dfecha,:cdetalle,
            :nv,:nigv,:nt,:cndo2,:cm,:ndolar,:ni,:ctg,:ccodp,:cmvto,:nus,'1','1',:n1,:n2,:n3,'0','0',:txtdetraccion,:nidauto,'027')";
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($ls);
            $st->execute([
                'ctdoc' => $cabecera["tdocv"],
                'cndoc' => $cabecera["ndoc"],
                'dfecha' => $cabecera["fechv"],
                'cform' => $cabecera["formv"],
                'cdetalle' => $cabecera["detav"],
                'nv' => $cabecera["subtotal"],
                'nigv' => $cabecera["igv"],
                'nt' => $cabecera["total"],
                'cndo2' => $cabecera['ndo2v'],
                'cm' => 'S',
                'ndolar' =>  session()->get('gene_dola'),
                'ni' => session()->get("gene_igv"),
                'ctg' => 'T',
                'ccodp' => $cabecera["idcliev"],
                'cmvto' => 'V',
                'nus' => $cabecera["nidus"],
                'n1' => session()->get('gene_idctav'),
                'n2' => session()->get('gene_idctai'),
                'n3' => session()->get('gene_idctat'),
                'txtdetraccion' => $cabecera["txtdetraccion"],
                'nidauto' => $cabecera["nidautov"]
            ]);

            $st->closeCursor();

            if ($st->errorCode() != '00000') {
                // \print_r($st->errorInfo());
                // \print_r($st->debugDumpParams());
                // \print_r($st->errorCode());
                $pdo->rollBack();
                $rpta = array('mensaje' => "No Se Actualizo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $sql = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,'',:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $query = $pdo->prepare($sql);
            $query->execute([
                'fechv' =>  $cabecera["fechv"],
                'cndocv' => $cabecera["ndoc"],
                'n3' => session()->get('gene_idctat'),
                'total' => $cabecera["total"],
                'nidus' => $cabecera["nidus"],
                'nidauto' => $cabecera["nidautov"],
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $cabecera["almv"]
            ]);
            // $query->debugDumpParams();
            if ($query->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $query->closeCursor();

            $lsqlcred = "Call ProActualizaCreditos(:nauto,:nu)";
            $stsqlcreditos = $pdo->prepare($lsqlcred);
            $stsqlcreditos->execute([
                "nauto" => $cabecera['nidautov'],
                "nu" => session()->get("usuario_id")
            ]);
            $stsqlcreditos->closeCursor();

            if ($stsqlcreditos->errorCode() != '00000') {

                $pdo->rollBack();
                $rpta = array('mensaje' => "Al actualizar ID de créditos", "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,1,'web') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $cabecera["nidautov"],
                    "nid" => $cabecera["idcliev"],
                    "cndoc" => $cabecera["ndoc"],
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $cabecera["fechv"],
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $cabecera["ndoc"],
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => 4,
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"]
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    // $st->debugDumpParams();
                    // print_r($st->errorCode());
                    // print_r($st->errorInfo());
                    $rpta = array('mensaje' => $stcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            $sql1 = "update fe_detallevta set detv_acti='I' where detv_idau=:nidauto";
            $query1 = $pdo->prepare($sql1);
            $query1->execute([
                'nidauto' =>  $cabecera["nidautov"]
            ]);
            // $query->debugDumpParams();
            if ($query1->errorCode() != '00000') {
                // \print_r($query1->errorInfo());
                // \print_r($query1->debugDumpParams());
                // \print_r($query1->errorCode());
                $pdo->rollBack();
                $rpta = array('mensaje' => "No Se Actualizo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $sql1 = "CALL ProIngresaDetalleVta(:cdesc,:nitem,'0','0',:nid,:nprecio,:ncant,:cunid)";
            // $carritov = session()->get('carritov', []);
            $i = 0;
            $sw = 1;
            foreach ($detalle as $item) {
                $query1 = $pdo->prepare($sql1);
                $i++;
                $desc = $item['descripcion'];
                $cant = floatval($item['cantidad']);
                $unidad = $item['unidad'];
                $prec = floatval($item['precio']);
                // $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                $query1->execute([
                    "cdesc" => $desc,
                    "nitem" => $i,
                    "nid" => $cabecera["nidautov"],
                    "nprecio" => $prec,
                    "ncant" => $cant,
                    "cunid" => $unidad
                ]);
                //  $query1->debugDumpParams();
                if ($query1->errorCode() != '00000') {
                    $sw = 0;
                    break;
                }
            }

            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => "No se actualizo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se actualizo satisfactoriamente", "ndoc" => $cabecera["ndoc"], "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            $rpta = array('mensaje' => "No se actualizo", "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function grabarVentaGeneral($cabecera)
    {
        $this->fechv = $cabecera["fechv"];
        $this->fechvv = $cabecera["fechvv"];
        $sql = "SELECT FunIngresaCabeceraVtasicbper(:tdocv,:formv,:cndocv,:fechv,:txtreferencia,:subtotal,:igv,:total,:ndo2v,:monev,
            :dola,:vigv,'K',:idcliev,'V',:nidus,:almv,:n1,:n2,:n3,'0','0','0.00',:vuelto) AS ID";

        if ($cabecera['tdocv'] == '01' || $cabecera['tdocv'] == '03') {
            $nidcta1 = session()->get("gene_idctav");
            $nidcta2 = session()->get("gene_idctai");
            $nidcta3 = session()->get("gene_idctat");
        } else {
            $nidcta1 = 0;
            $nidcta2 = 0;
            $nidcta3 = 0;
        }

        try {
            $correlativo = SerieController::correlativo($_SESSION['nserie'], $cabecera["tdocv"]);
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($sql);
            $st->execute([
                'tdocv' => $cabecera["tdocv"],
                'formv' => $cabecera["formv"],
                'cndocv' => $this->cndoc,
                'fechv' =>  $this->fechv,
                'subtotal' => $cabecera["subtotal"],
                'igv' => $cabecera["igv"],
                'total' => $cabecera["total"],
                'ndo2v' => $cabecera["ndo2v"],
                'monev' => $cabecera["monev"],
                'dola' => session()->get('gene_dola'),
                'vigv' => session()->get('gene_igv'),
                'nidus' => $cabecera["nidus"],
                'idcliev' => $cabecera["idcliev"],
                'almv' => $cabecera["almv"],
                'n1' => $nidcta1,
                'n2' => $nidcta2,
                'n3' => $nidcta3,
                'txtreferencia' => $cabecera["txtreferencia"],
                'vuelto' => empty($cabecera['txtvuelto']) ? '0' : $cabecera['txtvuelto']
            ]);
            if ($st->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sql, $st->errorInfo());
                $rpta = array('mensaje' => "No se registro en la cabecera", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $id = $st->fetchColumn();
            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,:nalma,'') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $id,
                    "nid" => $cabecera["idcliev"],
                    "cndoc" =>  $this->cndoc,
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $cabecera["fechv"],
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $this->cndoc,
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => $cabecera['idvenv'],
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"],
                    'nalma' => $_SESSION['idalmacen']
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    // $st->debugDumpParams();
                    // print_r($st->errorCode());
                    // print_r($st->errorInfo());
                    enviarmensajerror($sqlcreditos, $stcreditos->errorInfo());
                    $rpta = array('mensaje' => $stcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }
            if ($cabecera['formv'] == 'Y' || $cabecera['formv'] == 'P' || $cabecera['formv'] == 'T' || $cabecera['formv'] == 'D') {
                if (!empty($cabecera['txtpago'])) {
                    $cabecera["total"] = $cabecera['txtpago'];
                }
            }
            $sql = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,:deta,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $query = $pdo->prepare($sql);
            $query->execute([
                'fechv' =>  $this->fechv,
                'cndocv' => $this->cndoc,
                'deta' => $cabecera['razov'],
                'n3' => session()->get('gene_idctat'),
                'total' => $cabecera["total"],
                // 'total' => $cabecera["total"],
                'nidus' => $cabecera["nidus"],
                'nidauto' => $id,
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $cabecera["almv"]
            ]);
            // $query->debugDumpParams();
            if ($query->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sql, $query->errorInfo());
                $rpta = array('mensaje' => "No se la caja", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if (!empty(floatval($cabecera['txtefectivo']))) {
                $sqlefec = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,:deta,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
                $execefec = $pdo->prepare($sqlefec);
                $execefec->execute([
                    'fechv' =>  $this->fechv,
                    'cndocv' => $this->cndoc,
                    'deta' => $cabecera['razov'],
                    'n3' => session()->get('gene_idctat'),
                    'total' => $cabecera["txtefectivo"],
                    'nidus' => $cabecera["nidus"],
                    'nidauto' => $id,
                    'nidclie' => $cabecera["idcliev"],
                    'cform' => 'E',
                    'ctdoc' => $cabecera["tdocv"],
                    'almv' => $cabecera["almv"]
                ]);
                // $query->debugDumpParams();
                if ($execefec->errorCode() != '00000') {
                    $pdo->rollBack();
                    enviarmensajerror($sqlefec, $execefec->errorInfo());
                    $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }
            $sqliki = "SELECT FunIngresaKardexIcbper(:nid,:cc,'0',:npr,:nct,:igv,'K',:ccod,:calma,:nidcosto1,'0',:epta,:karunid,:karequi,:lote,:fechavto) AS NID";
            $carritov = session()->get('carritov', []);
            $sqlas = "CALL astock(:coda,:nalma,:ccant,'V',:cantequi)";
            $sw = 1;
            foreach ($carritov as $item) {
                if ($item['activo'] == 'A') {
                    if ($item['tipoproducto'] == 'K') {
                        $execas = $pdo->prepare($sqlas);
                        $cant = floatval($item['cantidad']);
                        $cantequi = floatval($item['cantequi']);
                        $execas->execute([
                            "coda" => $item['coda'],
                            "nalma" => $cabecera['almv'],
                            "ccant" => $cant,
                            "cantequi" => $cantequi
                        ]);
                        if ($execas->errorCode() != '00000') {
                            enviarmensajerror($sqlas, $execas->errorInfo());
                            $sw = 0;
                            break;
                        }
                    } else {
                        $producto = new Producto();
                        $producto->txtidart = $item['coda'];
                        $rpta = $producto->verdetallecombo();
                        foreach ($rpta['listado'] as $l) {
                            $execas = $pdo->prepare($sqlas);
                            $cant = floatval($item['cantidad']);
                            $cantequi = floatval($item['cantequi']);
                            $execas->execute([
                                "coda" => $l['idart'],
                                "nalma" => $cabecera['almv'],
                                "ccant" => $cant,
                                "cantequi" => $cantequi
                            ]);
                            if ($execas->errorCode() != '00000') {
                                enviarmensajerror($sqlas, $execas->errorInfo());
                                $sw = 0;
                                break;
                            }
                        }
                    }
                    $execiki = $pdo->prepare($sqliki);
                    $cant = floatval($item['cantidad']);
                    $prec = floatval($item['precio']);
                    $igv = $cabecera['optigv'];
                    $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                    $execiki->execute([
                        "nid" => $id,
                        "cc" => $item['coda'],
                        "npr" => $prec,
                        "nct" => $cant,
                        "ccod" => $cabecera['idvenv'],
                        "calma" => $cabecera['almv'],
                        "nidcosto1" => $costo,
                        'igv' => $igv,
                        'epta' => $item['presseleccionada'],
                        'karunid' => $item['unidad'],
                        'karequi' => $item['cantequi'],
                        'lote' => $item['lote'],
                        'fechavto' => $item['fechavto']
                    ]);
                    if ($execiki->errorCode() != '00000') {
                        enviarmensajerror($execiki, $execiki->errorInfo());
                        $sw = 0;
                        break;
                    }
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $rpta = array('mensaje' => "Se registro satisfactoriamente en el sistema", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }

    function actualizarVenta($cabecera)
    {
        $this->fechv = $cabecera["fechv"];
        $ls = "CALL ProActualizaCabeceraCVtasicbper(:ctdoc,:cform,:cndoc,:dfecha,:cdetalle,:nv,:nigv,:nt,:cndo2,:cm,:ndolar,:ni,:ctg,:ccodp,
            :cmvto,:nus,:nicbper,:nidcodt,:n1,:n2,:n3,:nitems,:npvta,:nidauto)";

        if ($cabecera['tdocv'] == '01' || $cabecera['tdocv'] == '03') {
            $nidcta1 = session()->get("gene_idctav");
            $nidcta2 = session()->get("gene_idctai");
            $nidcta3 = session()->get("gene_idctat");
        } else {
            $nidcta1 = 0;
            $nidcta2 = 0;
            $nidcta3 = 0;
        }

        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($ls);
            $st->execute([
                'ctdoc' => $cabecera["tdocv"],
                'cndoc' => $cabecera["cndocv"],
                'dfecha' =>  $cabecera["fechv"],
                'cform' => $cabecera["formv"],
                'cdetalle' => $cabecera["txtreferencia"],
                'nv' => $cabecera["subtotal"],
                'nigv' => $cabecera["igv"],
                'nt' => $cabecera["total"],
                'cndo2' => $cabecera["ndo2v"],
                'cm' => $cabecera["monev"],
                'ndolar' => session()->get('gene_dola'),
                'ni' =>  session()->get("gene_igv"),
                'ctg' => 'K',
                'ccodp' => $cabecera["idcliev"],
                'cmvto' => 'V',
                'nus' => $cabecera["nidus"],
                'nicbper' => '1',
                'nidcodt' => $cabecera["almv"],
                'n1' => $nidcta1,
                'n2' =>  $nidcta2,
                'n3' => $nidcta3,
                'nitems' => 0,
                'npvta' => '0',
                'nidauto' => $cabecera["nidautov"]
            ]);

            $st->closeCursor();

            if ($st->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($ls, $st->errorInfo());
                $rpta = array('mensaje' => $st->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            if ($cabecera['formv'] == 'Y' || $cabecera['formv'] == 'P' || $cabecera['formv'] == 'T' || $cabecera['formv'] == 'D') {
                if (!empty($cabecera['txtpago'])) {
                    $cabecera["total"] = $cabecera['txtpago'];
                }
            }

            $sqlicf = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,:deta,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $execicf = $pdo->prepare($sqlicf);
            $execicf->execute([
                'fechv' => $cabecera["fechv"],
                'cndocv' => $cabecera["cndocv"],
                'deta' => $cabecera['razov'],
                'n3' => session()->get('gene_idctat'),
                'total' => $cabecera["total"],
                // 'total' => $cabecera["txtpago"],
                'nidus' => $cabecera["nidus"],
                'nidauto' => $cabecera["nidautov"],
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $cabecera["almv"]
            ]);

            // $query->debugDumpParams();
            if ($execicf->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sqlicf, $execicf->errorInfo());
                $rpta = array('mensaje' => "No se actualizaron los créditos", "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            if (!empty(floatval($cabecera['txtefectivo']))) {
                $sqlefec = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,:deta,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
                $execefec = $pdo->prepare($sqlefec);
                $execefec->execute([
                    'fechv' => $cabecera["fechv"],
                    'cndocv' => $cabecera["cndocv"],
                    'deta' => $cabecera['razov'],
                    'n3' => session()->get('gene_idctat'),
                    'total' => $cabecera["txtefectivo"],
                    'nidus' => $cabecera["nidus"],
                    'nidauto' =>  $cabecera["nidautov"],
                    'nidclie' => $cabecera["idcliev"],
                    'cform' => 'E',
                    'ctdoc' => $cabecera["tdocv"],
                    'almv' => $cabecera["almv"]
                ]);

                // $query->debugDumpParams();
                if ($execefec->errorCode() != '00000') {
                    $pdo->rollBack();
                    enviarmensajerror($execefec, $execefec->errorInfo());
                    $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            $lsqlcred = "Call ProActualizaCreditos(:nauto,:nu)";
            $stsqlcreditos = $pdo->prepare($lsqlcred);
            $stsqlcreditos->execute([
                "nauto" => $cabecera['nidautov'],
                "nu" => session()->get("usuario_id")
            ]);

            $stsqlcreditos->closeCursor();

            if ($stsqlcreditos->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($lsqlcred, $stsqlcreditos->errorInfo());
                $rpta = array('mensaje' => $stsqlcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $sqldbcred = "UPDATE fe_cred SET acti='I' WHERE ndoc=:ndoc";
            $execdbcred = $pdo->prepare($sqldbcred);
            $execdbcred->execute([
                "ndoc" => $this->cndoc,
            ]);

            if ($stsqlcreditos->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sqldbcred, $execdbcred->errorInfo());
                $rpta = array('mensaje' => $execdbcred->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,:nalma,'') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $cabecera["nidautov"],
                    "nid" => $cabecera["idcliev"],
                    "cndoc" =>  $cabecera["cndocv"],
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $cabecera["fechv"],
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $this->cndoc,
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => $cabecera["idvenv"],
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"],
                    'nalma' => $_SESSION['idalmacen']
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    enviarmensajerror($sqlcreditos, $stcreditos->errorInfo());
                    $rpta = array('mensaje' => $stcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            $carritov = session()->get('carritov', []);

            $sqlas = "CALL ProActualizaStock(:coda,:nalma,:ccant,'V',:cantequi,:ccant)";
            $sqlinserta = "SELECT FunIngresaKardexIcbper(:nid,:cc,:nicbper,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,:nidcosto1,:vcom,:epta,:karunid,:karequi,:lote,:fechavto) AS IDD";
            $sqlactualiza = "CALL ProActualizaKardexICBPER(:nid,:cc,:nicbper,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,:nidcosto1,:nidkar,:op,:xcom,:epta,:karunid,:karequi,:lote,:fechavto)";
            $sw = 1;

            foreach ($carritov as $item) {
                if ($item['activo'] == 'A') {
                    if ($item['nreg'] == 0) {
                        $query = $pdo->prepare($sqlinserta);
                        $ncant = floatval($item['cantidad']);
                        $nprecio = floatval($item['precio']);
                        $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                        $query->execute([
                            "nid" => $cabecera["nidautov"],
                            "cc" => $item['coda'],
                            "nicbper" => '0',
                            "npr" => $nprecio,
                            "nct" => $ncant,
                            "cincl" => $cabecera["optigv"],
                            "tmvto" => 'K',
                            "ccodv" => $cabecera['idvenv'],
                            "calma" => $cabecera["almv"],
                            "nidcosto1" => $costo,
                            "vcom" => '0',
                            'epta' => $item['presseleccionada'],
                            'karunid' => $item['unidad'],
                            'karequi' => $item['cantequi'],
                            'lote' => $item['lote'],
                            'fechavto' => $item['fechavto']
                        ]);
                    } else {
                        $query = $pdo->prepare($sqlactualiza);
                        $ncant = floatval($item['cantidad']);
                        $nprecio = floatval($item['precio']);
                        $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                        $query->execute([
                            "nid" => $cabecera["nidautov"],
                            "cc" => $item['coda'],
                            "nicbper" => '0',
                            "npr" => $nprecio,
                            "nct" => $ncant,
                            "cincl" => $cabecera["optigv"],
                            "tmvto" => 'K',
                            "ccodv" => $cabecera['idvenv'],
                            "calma" => $cabecera["almv"],
                            "nidcosto1" => $costo,
                            "nidkar" => $item['nreg'],
                            "op" => '1',
                            "xcom" => '0',
                            'epta' => $item['presseleccionada'],
                            'karunid' => $item['unidad'],
                            'karequi' => $item['cantequi'],
                            'lote' => $item['lote'],
                            'fechavto' => $item['fechavto']
                        ]);
                    }
                } else {
                    if ($item['nreg'] > 0) {
                        $query = $pdo->prepare($sqlactualiza);
                        $ncant = floatval($item['cantidad']);
                        $nprecio = floatval($item['precio']);
                        $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                        $query->execute([
                            "nid" => $cabecera["nidautov"],
                            "cc" => $item['coda'],
                            "nicbper" => '0',
                            "npr" => $nprecio,
                            "nct" => $ncant,
                            "cincl" => $cabecera["optigv"],
                            "tmvto" => 'K',
                            "ccodv" => '0',
                            "calma" => $cabecera["almv"],
                            "nidcosto1" => $costo,
                            "nidkar" => $item['nreg'],
                            "op" => '0',
                            "xcom" => '0',
                            'epta' => $item['presseleccionada'],
                            'karunid' => $item['unidad'],
                            'karequi' => $item['cantequi'],
                            'lote' => $item['lote'],
                            'fechavto' => $item['fechavto']
                        ]);
                    }
                }

                if ($query->errorCode() != '00000') {
                    $sw = 0;
                    enviarmensajerror($sqlinserta . ' o ' . $sqlactualiza, $query->errorInfo());
                    break;
                }

                if ($item['tipoproducto'] == 'C') {
                    $producto = new Producto();
                    $producto->txtidart = $item['coda'];
                    $rpta = $producto->verdetallecombo();
                    foreach ($rpta['listado'] as $l) {
                        $execas = $pdo->prepare($sqlas);
                        $cant = floatval($item['cantidad']);
                        $cantequi = floatval($item['cantequi']);
                        $ncaant = floatval($item['caant']);
                        $execas->execute([
                            "coda" => $l['idart'],
                            "nalma" => $cabecera['almv'],
                            "ccant" => $cant,
                            "cantequi" => $cantequi,
                            "ncaant" => $ncaant
                        ]);
                    }
                } else {
                    $execas = $pdo->prepare($sqlas);
                    $cant = floatval($item['cantidad']);
                    $ncaant = floatval($item['caant']);
                    $karequi = floatval($item['cantequi']);
                    $execas->execute([
                        "coda" => $item['coda'],
                        "nalma" => $cabecera['almv'],
                        "ccant" => $ncaant,
                        'cantequi' => $karequi,
                        "ncaant" => $karequi * $ncaant
                    ]);
                }

                if ($execas->errorCode() != '00000') {
                    enviarmensajerror($sqlas, $execas->errorInfo());
                    $sw = 0;
                    break;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execas->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if ($query->errorCode() == '00000') {
                $pdo->commit();
                $ncon->close();
                $rpta = array('mensaje' => "Se actualizo correctamente", "ndoc" => $cabecera["cndocv"], "estado" => '1');
            }
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }

    function grabarVentaCanje($cabecera, $detallecanje)
    {
        $this->fechv = $cabecera["fechv"];
        $this->fechvv = $cabecera["fechvv"];

        $sqlacg = "CALL ProActualizaCanjeguia(:tdocv,:formv,:cndocv,:fechv,:fechvv,'',:subtotal,:igv,:total,:ndo2v,:monev,:dola,:vigv,'K',
        :idcliev,'V',:nidus,1,:almv,:n1,:n2,:n3,:iddire,:idautog,:idauto)";

        if ($cabecera['tdocv'] == '01' || $cabecera['tdocv'] == '03') {
            $nidcta1 = session()->get("gene_idctav");
            $nidcta2 = session()->get("gene_idctai");
            $nidcta3 = session()->get("gene_idctat");
        } else {
            $nidcta1 = 0;
            $nidcta2 = 0;
            $nidcta3 = 0;
        }

        try {
            $correlativo = SerieController::correlativo($_SESSION['nserie'], $cabecera["tdocv"]);
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];

            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $execacg = $pdo->prepare($sqlacg);
            $execacg->execute([
                'tdocv' => $cabecera["tdocv"],
                'formv' => $cabecera["formv"],
                'cndocv' => $this->cndoc,
                'fechv' =>  $cabecera["fechv"],
                'fechvv' =>  $cabecera["fechv"],
                'subtotal' => $cabecera["subtotal"],
                'igv' => $cabecera["igv"],
                'total' => $cabecera["total"],
                'ndo2v' => $cabecera["ndo2v"],
                'monev' => $cabecera["monev"],
                'dola' => session()->get('gene_dola'),
                'vigv' => session()->get("gene_igv"),
                'idcliev' => $cabecera["idcliev"],
                'nidus' => $cabecera["nidus"],
                'almv' => $_SESSION['idalmacen'],
                'n1' => ($nidcta1),
                'n2' => ($nidcta2),
                'n3' => ($nidcta3),
                'iddire' => $cabecera['iddire'],
                'idautog' => $cabecera["idautog"],
                'idauto' => $cabecera["idautov"]
            ]);

            if ($execacg->errorCode() != '00000') {
                $pdo->rollBack();
                // $st->debugDumpParams();
                // print_r($st->errorCode());
                enviarmensajerror($sqlacg, $execacg->errorInfo());
                $rpta = array('mensaje' => 'Error al registrar en la cabecera del sistema', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $execacg->closeCursor();
            // $id = $st->fetchColumn();

            $sqlidce = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,:deta,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $execidce = $pdo->prepare($sqlidce);
            $execidce->execute([
                'fechv' =>  $this->fechv,
                'cndocv' => $this->cndoc,
                'deta' => $cabecera['razov'],
                'n3' => session()->get('gene_idctat'),
                'total' => $cabecera["total"],
                'nidus' =>  session()->get("usuario_id"),
                'nidauto' => $cabecera["idautov"],
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $_SESSION['idalmacen']
            ]);

            // $query->debugDumpParams();
            if ($execidce->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sqlidce, $execidce->errorInfo());
                $rpta = array('mensaje' => "No se actualizo la caja", "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $execidce->closeCursor();

            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,:nalma,'web') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $cabecera["idautov"],
                    "nid" => $cabecera["idcliev"],
                    "cndoc" => $this->cndoc,
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $this->fechv,
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $this->cndoc,
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => $cabecera["idvenv"],
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"],
                    "nalma" => $_SESSION['idalmacen']
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    enviarmensajerror($sqlcreditos, $stcreditos->errorInfo());
                    $rpta = array('mensaje' => 'Ocurrió un error al generar los créditos', "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            $sw = 1;
            $sqlk = "update fe_kar set prec=:prec and incl=:igv where idart=:idart and idauto=:idauto";

            $idauto = $cabecera["idautov"];
            $optigv = $cabecera['optigv'];

            foreach ($detallecanje as $item) {
                $execk = $pdo->prepare($sqlk);
                $execk->execute([
                    "prec" => $item['precio'],
                    "idart" => $item["id"],
                    "igv" => $optigv,
                    "idauto" => $idauto
                ]);
                if ($execk->errorCode() != '00000') {
                    $sw = 0;
                    enviarmensajerror($sqlk, $execk->errorInfo());
                    break;
                }
            }

            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execk->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => "Error al actualizar correlativo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => "No se registro" . $pdo_error->getMessage(), "ndoc" => '', "estado" => '0');
        }
        return $rpta;
    }
    function grabarventacanjetr($cabecera, $detalle)
    {
        try {
            $correlativo = SerieController::correlativo($_SESSION['nserie'], $cabecera["tdocv"]);
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];
            $ls = "SELECT FuningresaDocumentoElectronico(
                :tdocv,:formv,:cndocv,:fechv,'web', :subtotal,:igv,:total,:ndo2v,:monev,
                :dola,:vigv,'T',:idcliev,'V',:nidus,:almv,:n1,:n2,:n3,'027','0.00','0.00',:detraccion) AS ID";
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($ls);
            $st->execute([
                'tdocv' => $cabecera["tdocv"],
                'formv' => $cabecera["formv"],
                'cndocv' => $this->cndoc,
                'fechv' => $cabecera["fechv"],
                'subtotal' => $cabecera["subtotal"],
                'igv' => $cabecera["igv"],
                'total' => $cabecera["total"],
                'ndo2v' => $cabecera["ndo2v"],
                'monev' => $cabecera['monev'],
                'dola' => session()->get("gene_dola"),
                'vigv' => session()->get("gene_igv"),
                'nidus' => $cabecera["nidus"],
                'idcliev' => $cabecera["idcliev"],
                'almv' => $_SESSION['idalmacen'],
                'n1' => session()->get("gene_idctav"),
                'n2' => session()->get("gene_idctai"),
                'n3' => session()->get("gene_idctat"),
                'detraccion' => $cabecera["detraccion"]
            ]);
            if ($st->errorCode() != '00000') {
                $pdo->rollBack();
                // $st->debugDumpParams();
                // print_r($st->errorCode());
                $rpta = array('mensaje' => $st->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $id = $st->fetchColumn();

            $st->closeCursor();

            $sqlacguiatr = "update fe_guiastr set guia_idau=:nid where guia_idgui=:nidg";
            $stguiastr = $pdo->prepare($sqlacguiatr);
            $stguiastr->execute([
                'nid' => $id,
                'nidg' => session()->get('idautog')
            ]);

            if ($stguiastr->errorCode() != '00000') {
                $pdo->rollBack();
                // $st->debugDumpParams();
                // print_r($st->errorCode());
                $rpta = array('mensaje' => $stguiastr->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $stguiastr->closeCursor();

            $sqlidc = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,:deta,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $execidc = $pdo->prepare($sqlidc);
            $execidc->execute([
                'fechv' =>  $cabecera["fechv"],
                'cndocv' => $this->cndoc,
                'deta' => $cabecera['razov'],
                'n3' => session()->get('gene_idctat'),
                'total' => $cabecera["total"],
                'nidus' => $cabecera["nidus"],
                'nidauto' => $id,
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $_SESSION['idalmacen']
            ]);

            // $query->debugDumpParams();
            if ($execidc->errorCode() != '00000') {
                // \print_r($query->errorInfo());
                // \print_r($query->debugDumpParams());
                // \print_r($query->errorCode());
                $pdo->rollBack();
                $rpta = array('mensaje' => $execidc->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $execidc->closeCursor();

            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,1,'web') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $id,
                    "nid" => $cabecera["idcliev"],
                    "cndoc" => $this->cndoc,
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $cabecera["fechv"],
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $this->cndoc,
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => $cabecera['idvenv'],
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"],
                    "nalma" => $_SESSION['idalmacen']
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    // $st->debugDumpParams();
                    // print_r($st->errorCode());
                    // print_r($st->errorInfo());
                    $rpta = array('mensaje' => $stcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            $sqlidv = "CALL ProIngresaDetalleVta(:cdesc,:nitem,'0','0',:nid,:nprecio,:ncant,:cunid)";

            $i = 0;
            $sw = 1;
            foreach ($detalle as $item) {
                $execidv = $pdo->prepare($sqlidv);
                $i++;
                $desc = $item['descripcion'];
                $cant = floatval($item['cantidad']);
                $unidad = $item['unidad'];
                $prec = floatval($item['precio']);
                // $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                $execidv->execute([
                    "cdesc" => $desc,
                    "nitem" => $i,
                    "nid" => $id,
                    "nprecio" => $prec,
                    "ncant" => $cant,
                    "cunid" => $unidad
                ]);
                if ($execidv->errorCode() != '00000') {
                    $sw = 0;
                    break;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execidv->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => 'Error al aumentar correlativo', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function anularVentaPorID($id, $idusua)
    {
        try {
            $sql = "call ProAnulaTransacciones(@estado,'','','V',:id,:nu,'S',:dfecha,:nu1,:idtienda)";
            $st = $this->prepare($sql);
            $st->execute([
                'id' => $id,
                'nu' => session()->get("usuario_id"),
                'dfecha' => date('Y-m-d'),
                'nu1' => $idusua,
                'idtienda' => $_SESSION['idalmacen']
            ]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    function buscarDetalleAnularVenta($num, $tdoc, $tipom)
    {
        $sql = "SELECT a.idauto,a.ndoc,a.fech,a.mone,b.razo,a.impo AS importe,a.idcliente AS codi,idauto,form,a.idusua AS idusuav,rcom_mens,LEFT(rcom_mens,1) AS estadoenviado,tdoc FROM
                fe_rcom AS a JOIN fe_clie AS b ON(a.idcliente=b.idclie) WHERE a.ndoc=:num AND tdoc=:tdoc AND tipom=:tipom and a.acti='A'";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'num' => $num,
            'tdoc' => $tdoc,
            'tipom' => $tipom
        ]);
        return $query;
    }
    function consultarVentasPorCliente($idCliente)
    {
        $sql = "SELECT ndoc AS dcto,a.fech,b.nruc,b.razo,IF(a.mone='S','Soles','Dólares') AS mone,
        a.valor,a.rcom_exon,CAST(0 AS DECIMAL(12,2)) AS inafecto,b.idclie,b.ndni,
        a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,
        CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml,tcom
        FROM fe_rcom AS a 
        INNER JOIN fe_clie AS b ON (a.idcliente=b.idclie),fe_gene AS v
        WHERE a.acti='A' AND impo<>0 AND a.`idcliente`=:idCliente and tdoc not in ('07','08','09') ORDER BY fech desc";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idCliente' => $idCliente
        ]);
        return $query;
    }
    function consultarDetalleVtaDirecta($idauto)
    {
        $sql = "SELECT idkar,a.idart,a.`descri`,idauto,k.`prec`,cant,acti as activo,kar_unid as unidad,kar_epta,kar_equi
        FROM fe_kar k
        INNER JOIN fe_art a ON k.`idart`=a.`idart` 
        WHERE idauto=:idauto and acti='A'";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idauto' => $idauto
        ]);
        return $query;
    }
    function consultarDetalleVtaServicio($idauto)
    {
        $sql = "SELECT detv_idvt AS idkar, '1' AS idart, 'SERVICIO DE TRANSPORTE' AS descri,detv_idau AS idauto,detv_prec AS prec,detv_cant AS cant, detv_acti AS activo
        FROM fe_detallevta
        WHERE detv_idau=:idauto AND detv_acti='A'";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idauto' => $idauto
        ]);
        return $query;
    }
    // function registroventasple($mes, $ano)
    // {
    //     // $sql = "select a.form,a.fecr,a.fech,a.tdoc,if(length(trim(a.ndoc))<=10,LEFT(a.ndoc,3),left(a.ndoc,4)) as serie,
    //     // if(length(trim(a.ndoc))<=10,MID(a.ndoc,4,7),Mid(a.ndoc,5,8)) as ndoc,
    //     // b.nruc,b.razo,if(a.mone='S',a.valor,round(a.valor*a.dolar,2)) as valor,
    //     // if(a.mone='S',rcom_exon,round(rcom_exon*a.dolar,2)) as exon,
    //     // if(a.mone='S',rcom_inaf,round(rcom_inaf*a.dolar,2)) as inafecto,
    //     // if(a.mone='S',a.igv,round(a.igv*a.dolar,2)) as igv,
    //     // if(a.mone='S',a.impo,round(a.impo*a.dolar,2)) as importe,
    //     // if(a.mone='S',rcom_otro,round(rcom_otro*a.dolar,2)) as grati,
    //     // a.pimpo,rcom_icbper as icbper,
    //     // a.mone,a.dolar as dola,a.vigv,a.idcliente as codigo,
    //     // a.deta as detalle,a.idauto,b.ndni,rcom_mens as mensaje,tcom 
    //     // FROM fe_rcom as a inner join fe_clie  as b ON(b.idclie=a.idcliente)
    //     // where month(fech)=:mes and year(fech)=:ano and tdoc in('01','07','08','03') and acti<>'I'" . $a . " order by fecr,ndoc";

    //     $sql = " Select a.form,a.fecr,a.fech,a.tdoc,Left(a.Ndoc,4) As serie,tt.nomb as tipodoc, 
    //     If(Length(Trim(a.Ndoc))<=10,mid(a.Ndoc,4,7),mid(a.Ndoc,5,8)) As ndoc,
    //     b.nruc,b.razo,a.valor,rcom_exon As exon,a.igv,a.Impo As importe,rcom_otro As grati,a.pimpo,rcom_icbper As icbper,
    //     if(a.mone='S',rcom_inaf,round(rcom_inaf*a.dolar,2)) as inafecto,
    //     a.mone,a.dolar As dola,a.vigv,a.idcliente As codigo,
    //     a.Deta As detalle,a.idauto,b.ndni,rcom_mens As mensaje,ifnull(p.Fevto,a.fech) As fvto From fe_rcom As a
    //     inner Join fe_clie  As b On(b.idclie=a.idcliente)
    //     inner join fe_tdoc as tt on (a.tdoc=tt.tdoc)
    //     Left Join (Select rcre_idau,Min(c.Fevto) As Fevto From fe_rcred As r inner Join fe_cred As c On c.cred_idrc=r.rcre_idrc Where rcre_acti='A' And Acti='A' And month(fech)=:mes and year(fech)=:ano Group By rcre_idau)  As p On p.rcre_idau=a.Idauto
    //     Where month(fech)=:mes and year(fech)=:ano and a.tdoc In('01','07','08','03')  and impo<>0 And Acti<>'I' Order By fecr,Ndoc";

    //     $exec = $this->prepare($sql);
    //     $exec->setFetchMode(PDO::FETCH_ASSOC);
    //     $exec->execute([
    //         'mes' => $mes,
    //         'ano' => $ano
    //     ]);
    //     $query = $exec->fetchAll(PDO::FETCH_ASSOC);
    //     return $query;
    // }
    // function registroventasnc($mes, $ano)
    // {
    //     $sql = "SELECT a.ndoc,a.tdoc,a.fech,b.ncre_idnc AS idn,ncre_idan FROM (SELECT ncre_idnc,ncre_idau,ncre_idan FROM fe_ncven AS n
    //             INNER JOIN fe_rcom AS r ON r.idauto=n.ncre_idan
    //             WHERE MONTH(r.fech)=:mes AND YEAR(r.fech)=:ano AND r.acti='A' AND ncre_acti='A' ) AS b
    //             INNER JOIN fe_rcom AS a ON a.idauto=b.ncre_idau";
    //     $exec = $this->prepare($sql);
    //     $exec->setFetchMode(PDO::FETCH_ASSOC);
    //     $exec->execute([
    //         'mes' => $mes,
    //         'ano' => $ano
    //     ]);
    //     $query = $exec->fetchAll(PDO::FETCH_ASSOC);
    //     return $query;
    // }
    function mostrarresumenvtasvendedor($dfi, $dff, $nidv)
    {
        // $sql = "SELECT c.nomv AS nomb,e.mone,d.razo,e.idcliente,SUM(e.impo) AS impo,SUM(e.`valor`) AS valor,SUM(e.igv) AS igv FROM
        // (SELECT e.idauto,
        // if(mone='S',valor,round(valor*dolar,2)) as valor,
        // if(mone='S',igv,round(igv*dolar,2)) as igv,
        // if(mone='S',impo,round(impo*dolar,2)) as impo,
        // idcliente,k.codv,mone  FROM fe_rcom AS e 
        // INNER JOIN fe_kar AS k ON k.idauto=e.idauto WHERE e.ACTI<>'I' AND k.acti<>'I' 
        // AND e.fech  BETWEEN :dfi AND :dff AND k.`codv`=:nidv
        // GROUP BY idcliente,k.codv,mone) AS e
        // INNER JOIN fe_clie AS d ON d.idclie=e.idcliente 
        // inner JOIN fe_vend AS c ON c.idven=e.codv GROUP BY e.idcliente,nomv,mone ";
        $sql = "SELECT e.idauto,c.nomv AS nomb,e.mone,d.razo,e.idcliente,IF(mone='S',impo,ROUND(impo*dolar,2)) AS impo,
                IF(mone='S',valor,ROUND(valor*dolar,2)) AS valor,
                IF(mone='S',igv,ROUND(igv*dolar,2)) AS igv
                FROM fe_rcom AS e
                INNER JOIN fe_kar AS k ON e.`idauto`=k.`idauto`
                INNER JOIN fe_clie AS d ON d.idclie=e.idcliente
                INNER JOIN fe_vend AS c ON c.idven=k.codv
                WHERE fech BETWEEN :dfi AND :dff
                AND k.`codv`=:nidv AND idclie>0 and impo<>0 group by e.idauto,ndoc";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'dfi' => $dfi,
            'dff' => $dff,
            'nidv' => $nidv
        ]);
        return $query;
    }
    function listarVentasxProducto($dfi, $dff)
    {
        $sql = "SELECT a.`prod_cod1` AS 'CODIGO',a.`descri` AS 'PRODUCTO',TRIM(m.`dmar`) AS 'MARCA',TRIM(k.`kar_unid`) AS 'UNIDAD',
            TRIM(g.`desgrupo`) AS 'GRUPO', TRIM(c.`dcat`) AS 'LINEA'
            FROM fe_art a
            INNER JOIN fe_mar m ON a.`idmar`=m.`idmar`
            INNER JOIN fe_cat c ON a.`idcat`=c.`idcat`
            INNER JOIN fe_grupo g ON c.`idgrupo`=g.`idgrupo`
            INNER JOIN fe_kar k ON a.`idart`=k.`idart`
            INNER JOIN fe_rcom r ON k.`idauto`=r.`idauto`
            INNER JOIN fe_sucu s ON k.`alma`=s.`idalma`
            where tcom='k' AND r.`acti`='A' AND k.`acti`='A' and r.idprov=0
            AND a.`prod_acti`='A' AND 
            r.`fech` BETWEEN :dfi AND :dff
            GROUP BY a.`idart`,kar_unid";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            "dfi" => $dfi,
            "dff" => $dff
        ]);
        return $query;
    }
    function listarVentasxCliente($dfi, $dff, $idclie)
    {
        $sql = "SELECT x.fech,x.fecr,x.tdoc,x.ndoc,x.ndo2,x.mone,
        if(x.mone='S',valor,round(valor*x.dolar,2)) as valor,x.pimpo,
        if(x.mone='S',rcom_exon,round(rcom_exon*x.dolar,2)) as exon,
        if(x.mone='S',rcom_inaf,round(rcom_inaf*x.dolar,2)) as inafecto,
        if(x.mone='S',x.igv,round(x.igv*x.dolar,2)) as igv,
        if(x.mone='S',x.impo,round(x.impo*x.dolar,2)) as impo,
        x.dolar AS dola,x.form,x.idauto,x.idcliente,
        y.cant,y.prec,ROUND(y.cant*y.prec,2) AS importe,dsnc,dsnd,gast,
        z.descri,z.unid,w.nomb AS usuario,x.fusua FROM fe_rcom x
        INNER JOIN fe_kar y ON y.idauto=x.idauto
        INNER JOIN fe_usua w ON w.idusua=x.idusua
        INNER JOIN fe_art z  ON z.idart=y.idart
        WHERE x.fech BETWEEN :dfi AND :dff AND x.idcliente=:idclie
        AND x.acti='A' AND tdoc IN (01,03,20) and x.idcliente<>0 AND y.acti='A' ORDER BY fech,x.tdoc,x.ndoc";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            "dfi" => $dfi,
            "dff" => $dff,
            "idclie" => $idclie
        ]);
        return $query;
    }
    function listarventastocanje()
    {
        try {
            $sql = "select ndoc as dcto,a.fech,b.nruc,b.razo,if(a.mone='S','Soles','Dólares') as mone,
                    a.valor,a.rcom_exon,CAST(0 as decimal(12,2)) as inafecto,
                    a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,idcliente,b.`dire`,b.`ubig`,
                    CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml
                    FROM fe_rcom as a 
                    inner JOIN fe_clie as b ON (a.idcliente=b.idclie),fe_gene as v
                    where a.acti='A' and tdoc<>'09' and tdoc<>'07' and MONTH(a.fech)=MONTH(LOCALTIME()) and codt=:codt order by fech desc";
            $query = $this->prepare($sql);
            $query->fetchAll(PDO::FETCH_ASSOC);
            $query->execute([
                'codt' => $_SESSION['idalmacen']
            ]);
            return $query;
        } catch (PDOException $e) {
            echo ('Error al Consultar ' . $e->getMessage());
        }
    }
    function listardetallevtatocanje($idauto)
    {
        try {
            $sql = "SELECT a.idart,descri,k.kar_unid as unid,cant,idkar,idauto,kar_equi,kar_epta FROM fe_kar k
            inner join fe_art a on k.idart=a.idart
            WHERE idauto=:idauto and k.acti='A'";
            $query = $this->prepare($sql);
            $query->fetchAll(PDO::FETCH_ASSOC);
            $query->execute([
                'idauto' => $idauto
            ]);
            return $query;
        } catch (PDOException $e) {
            echo ('Error al consultar ' . $e->getMessage());
        }
    }
    function consultardetalleventa($idauto)
    {
        try {
            $sql = "SELECT a.idart,descri,p.pres_desc AS unid,cant,idkar,idauto,kar_equi,kar_epta,kar_unid,k.prec,a.peso,k.kar_equi AS cantequi
            FROM fe_kar k
            INNER JOIN fe_art a ON k.idart=a.idart
            INNER JOIN fe_epta AS e ON k.`kar_epta`=e.`epta_idep`
            INNER JOIN fe_presentaciones p ON e.epta_pres=p.`pres_idpr`
            WHERE idauto=:idauto AND k.acti='A'";
            $query = $this->prepare($sql);
            $query->execute([
                'idauto' => $idauto
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al consultar ' . $e->getMessage());
        }
    }
    function mostrarventasutilidades($dfi, $dff, $cmbAlmacen)
    {
        try {
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
            // $m = ($cmbmoneda == '0') ? ' and mone<>:cmbmoneda  ' : ' and mone=:cmbmoneda ';
            $sql = "SELECT Ndoc,fech,cliente,Vendedor,Importe,(SUM(Utilidad)*100)/SUM(costototal) AS porcentaje,SUM(Utilidad) AS Utilidad,
                    Idauto FROM (SELECT k.idart AS Coda,b.Descri,k.kar_unid,cant,CAST(kar_cost AS DECIMAL(12,4)) AS costounitario,
                    CAST(IF(c.Mone='S',k.Prec,k.Prec*c.dolar) AS DECIMAL(12,4))AS PrecioVenta, CAST((cant)*(k.kar_cost* k.kar_equi) AS DECIMAL(12,2)) AS costototal, 
                    CAST(cant*IF(c.Mone='S',k.Prec,k.Prec*c.dolar) AS DECIMAL(12,2)) AS ventatotal, 
                    CAST(((cant)*IF(c.Mone='S',k.Prec,k.Prec*c.dolar))-((cant)*(k.kar_cost  * k.kar_equi )) AS DECIMAL(12,2))/kar_equi AS Utilidad,
                    cc.Razo AS cliente,v.`nomv` AS Vendedor,c.idauto,Ndoc,fech,IF(c.Mone='S',Impo,Impo*c.dolar) AS Importe 
                    FROM fe_rcom AS c 
                    INNER JOIN fe_kar AS k ON k.idauto=c.idauto
                    INNER JOIN fe_art AS b ON b.idart=k.idart
                    INNER JOIN fe_clie AS cc ON cc.idclie=c.idcliente
                    INNER JOIN fe_vend AS v ON v.idven=k.Codv
                    WHERE k.Acti='A' AND c.Acti='A' AND c.fech BETWEEN :dfi AND :dff" . $a . "AND c.tcom<>'T' ) 
                    AS xx GROUP BY idauto ORDER BY fech,Ndoc";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'cmbAlmacen' => $cmbAlmacen
            ]);
            return $query;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
}
