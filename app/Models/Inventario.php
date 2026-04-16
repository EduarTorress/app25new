<?php

namespace App\Models;

use PDO;
use Core\Routing\Modelo;

class Inventario extends Modelo
{
    public $dfechai = "";
    public $dfechaf = "";
    public $ncoda = 0;
    public $ntienda = 0;

    function listarkardex()
    {
        $sql = "SELECT ifnull(e.ndoc,'')  as nped,d.ndo2,d.fech,d.ndoc,d.tdoc,a.tipo,d.mone as cmoneda,a.cant * a.kar_equi as cant,d.fusua,ifnull(g.nomb,'') as usua1,d.codt,
            a.prec,d.vigv as igv,d.dolar,f.nomb as usua,d.idcliente as codc,b.razo AS cliente,d.idprov as codp,c.razo AS proveedor,d.deta,a.alma,kar_unid,cant as cantpres
            FROM fe_kar as a
            inner JOIN fe_rcom as d on (d.idauto=a.idauto)
            left join fe_prov as c ON(d.idprov=c.idprov)
            left JOIN fe_clie as b ON(d.idcliente=b.idclie)
            LEFT JOIN fe_rped as e ON(e.idautop=d.idautop)
            inner join fe_usua as f ON(f.idusua=d.idusua)
            left join fe_usua as g   ON (g.idusua=d.idusua1)
            WHERE a.idart=:nidart and a.alma=:ntienda and d.acti<>'I' and d.fech<=:dff
            and a.acti<>'I' ORDER BY d.fech,d.tipom,a.idkar";
        $query = $this->prepare($sql);
        $query->execute([
            'nidart' => $this->ncoda,
            'ntienda' => $this->ntienda,
            'dff' => $this->dfechaf
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listarexistenciaalmacen()
    {
        $sql = "SELECT a.idart,c.descri,kar_unid as unid,cant*kar_equi as cant,a.prec AS precio,
        tipo,d.dolar AS dola,d.idauto,d.mone
        FROM fe_kar AS a
        INNER JOIN fe_art AS c ON(c.idart=a.idart)
        INNER JOIN fe_rcom AS d ON(d.idauto=a.idauto)
        WHERE fech<=:fech AND a.acti<>'I' AND d.acti<>'I' AND d.tcom<>'T' ORDER BY a.idart,fech,a.tipo,d.tdoc,d.ndoc";
        $query = $this->prepare($sql);
        $query->execute([
            'fech' => $this->dfechaf
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listarajustes($fechi, $fechf, $cmbAlmacen)
    {
        $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen' : ' and codt=:cmbAlmacen ';
        $sql = "SELECT r.*,u.nomb FROM fe_rcom r
        inner join fe_usua u on r.idusua=u.idusua
        WHERE tdoc='AJ' AND acti='A' AND fech between :fechi and :fechf and acti='A'" . $a . " order by ndoc, fech";
        $query = $this->prepare($sql);
        $query->execute([
            'fechi' => $fechi,
            'fechf' => $fechf,
            'cmbAlmacen' => $cmbAlmacen
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listarstockxfechavencimiento($fecha, $cmbalmacen)
    {
        $almacen = ($cmbalmacen == '0' ? " " : " and k.alma='" . $cmbalmacen . "' ");
        $sql = "SELECT a.idart,descri,unid,prod_unid1,prod_equi1,prod_equi2,b.uno AS cant,kar_lote,kar_fvto,z.uno AS stock,dcat AS linea,a.prod_cod1 FROM
                (SELECT idart, idkar,cant*kar_equi AS uno,CAST(0 AS DECIMAL(10,2)) AS dos,CAST(0 AS DECIMAL(10,2)) AS tres,
                CAST(0 AS DECIMAL(12,2)) AS cuatro,kar_lote,kar_fvto
                FROM fe_kar AS k
                INNER JOIN fe_rcom AS r ON r.Idauto = k.Idauto
                WHERE r.fech <= :fecha AND r.Acti <> 'I' AND k.Acti <> 'I' AND tipo='C' AND cant>0 ORDER BY idkar DESC)
                AS b
                INNER JOIN fe_art AS a ON a.idart=b.idart
                INNER JOIN fe_cat AS c ON c.idcat=a.idcat
                INNER JOIN
                (SELECT idart, SUM(CASE k.alma WHEN 1 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS uno,
                SUM(CASE k.alma WHEN 2 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS Dos,
                SUM(CASE k.alma WHEN 3 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS tres,
                SUM(CASE k.alma WHEN 4 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS cuatro
                FROM fe_kar AS k
                INNER JOIN fe_rcom AS r ON r.Idauto = k.Idauto
                WHERE r.fech <= :fecha AND r.Acti <> 'I' AND k.Acti <> 'I' " . $almacen . " GROUP BY k.idart HAVING uno>0  ORDER BY idart)
                AS z ON z.idart=b.idart and prod_acti='A' ORDER BY idart,idkar DESC";
        $query = $this->prepare($sql);
        $query->execute([
            'fecha' => $fecha,
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function verdetalleajuste($idauto)
    {
        $sql = "SELECT a.descri,k.cant FROM fe_kar k INNER JOIN fe_art a ON k.idart=a.idart WHERE idauto=:idauto";
        $query = $this->prepare($sql);
        $query->execute([
            'idauto' => $idauto
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function calcularstock()
    {
        $sql = "CALL calcularstock()";
        $exec = $this->prepare($sql);
        $exec->execute();
    }
    function listarstockxalmacen($fechf, $cmbAlmacen)
    {
        $sql = "SELECT  a.idart AS Nreg, a.idart, b.Descri AS descri, b.prod_unid1, b.Unid, a.uno, a.Dos, a.tres, a.cuatro, (a.uno + a.Dos + a.tres + a.cuatro) AS Total,
                ((a.uno + a.Dos + a.tres + a.cuatro)) AS subtotal,b.prod_equi1 AS equi,b.prod_cod1,CAST(alma AS DECIMAL(10,2)) AS alma
                FROM (SELECT idart, SUM(CASE k.alma WHEN 1 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS uno,
                SUM(CASE k.alma WHEN 2 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS Dos,
                SUM(CASE k.alma WHEN 3 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS tres,
                SUM(CASE k.alma WHEN 4 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS cuatro,
                SUM(CASE k.alma WHEN 5 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS cinco,
                SUM(CASE k.alma WHEN 6 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS seis";
        switch ($cmbAlmacen) {
            case "1":
                $sql .= ",SUM(CASE k.alma WHEN 1 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS alma ";
                break;
            case "2":
                $sql .= "  ,SUM(CASE k.alma WHEN 2 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS alma ";
                break;
            case "3":
                $sql .= ",SUM(CASE k.alma WHEN 3 THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS alma ";
                break;
            case "4":
                $sql .= ",SUM(CASE k.alma  WHEN 4  THEN IF(Tipo = 'C', cant * k.kar_equi, - cant * k.kar_equi) ELSE 0 END) AS alma ";
                break;
        }
        $sql .= "FROM
                fe_kar AS k INNER JOIN fe_rcom AS r ON r.Idauto = k.Idauto
                WHERE r.fech <= :fechf AND r.Acti <> 'I' AND k.Acti <> 'I' GROUP BY k.idart ) AS a
                INNER JOIN fe_art AS b ON b.idart = a.idart
                WHERE b.prod_acti <> 'I' order by alma desc";
        $query = $this->prepare($sql);
        $query->execute([
            'fechf' => $fechf
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
}
