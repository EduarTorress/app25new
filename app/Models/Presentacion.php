<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class Presentacion extends Modelo
{
    function listar($cbuscar, $dolar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call ProMuestraPresentacionesXProducto(:abuscar,:dolar)";
        $query = $this->prepare($csql);
        try {
            $query->execute(['abuscar' => $cbuscar, 'dolar' => $dolar]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "pres_desc" => $row['pres_desc'],
                        "precio1" => $row['precio1'],
                        "epta_cant" => $row['epta_cant'],
                        "epta_idep" => $row['epta_idep'],
                        "epta_prec" => $row['epta_prec'],
                        "costo" => $row['costo']
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar " . $e->getMessage()];
        }
        return $data;
    }
    function listarpresentaciones()
    {
        $sql = "SELECT * FROM fe_presentaciones WHERE pres_acti='A'";
        $query = $this->prepare($sql);
        $query->fetchAll(PDO::FETCH_ASSOC);
        $query->execute();
        return $query;
    }
    function listardetapresxproducto($idart)
    {
        $sql = "SELECT e.*,p.pres_desc
                FROM fe_epta e
                INNER JOIN `fe_presentaciones` p ON (e.epta_pres=p.pres_idpr)
                WHERE p.pres_acti='A' and e.epta_idar=:idart AND e.`epta_acti`='A'";
        $query = $this->prepare($sql);
        $query->execute([
            'idart' => $idart
        ]);
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    function registrardetapresent($idpres, $idart, $prec, $cant)
    {
        try {
            $sql = "INSERT INTO fe_epta(epta_idar,epta_pres,epta_prec,epta_cant) VALUES (:idart,:idpres,:prec,:cant)";
            $exec = $this->prepare($sql);
            $exec->execute([
                'idpres' => $idpres,
                'idart' => $idart,
                'prec' => $prec,
                'cant' => $cant
            ]);
            $data = ["estado" => true, 'lista' => [], 'mensaje' => 'Se registro correctamente'];
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => [], 'mensaje' => "Error al conectar " . $e];
        }
        return $data;
    }

    function eliminardetapres($id)
    {
        try {
            $sql = "update fe_epta set epta_acti='I' where epta_idep=:id";
            $exec = $this->prepare($sql);
            $exec->execute([
                'id' => $id
            ]);
            $data = ["estado" => true, 'lista' => [], 'mensaje' => 'Se elimino correctamente'];
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => [], 'mensaje' => "Error al conectar " . $e];
        }
        return $data;
    }
}
