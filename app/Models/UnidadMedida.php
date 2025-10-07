<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use Exception;
use PDO;
use PDOException;

class UnidadMedida extends Modelo
{
    var $nombre = "";
    var $cantidad = "";
    function listar($cbuscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $sql = "select * from fe_presentaciones where pres_desc like :abuscar";
        $query = $this->prepare($sql);
        try {
            $query->execute(['abuscar' => $cbuscar]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "pres_idpr" => $row['pres_idpr'],
                        "pres_desc" => $row['pres_desc'],
                        "pres_cant" => $row['pres_cant']
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar " . $e];
        }
        return $data;
    }
    function buscarid($id)
    {
        $unidadmedida = array();
        $csql = 'select pres_desc,pres_cant from fe_presentaciones where pres_idpr=:id';
        $query = $this->prepare($csql);
        $query->execute(["id" => $id]);
        foreach ($query as $row) {
            $unidadmedida = array(
                "pres_desc" => $row['pres_desc'],
                "pres_cant" => $row['pres_cant']
            );
        }
        return $unidadmedida;
    }
    function save()
    {
        $ncon = new conexion();
        $pdo = $ncon->conectar();
        $sql = "INSERT INTO fe_presentaciones(pres_desc,pres_cant) VALUES (:txtnombre,:txtcantidad)";
        $query = $pdo->prepare($sql);
        $query->execute([
            'txtnombre' => $this->nombre,
            'txtcantidad' => $this->cantidad
        ]);
        $id = $pdo->lastInsertId();
        $rpta = ['mensaje' => 'Todo ok', 'id' => $id, 'estado' => '1'];
        return $rpta;
    }
}
