<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class TiposUsuario extends Modelo
{
    function listarTipos()
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "SELECT DISTINCT(tipo) FROM `fe_usua` WHERE tipo<>'' AND activo='S'";
        $query = $this->prepare($csql);
        try {
            $query->execute();
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "tipo" => $row['tipo']
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
}
