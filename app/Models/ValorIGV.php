<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class ValorIGV extends Modelo
{
    function obtenerIGV()
    {
        try {
            $sql = "SELECT igv FROM fe_gene";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            $_SESSION['igv'] = $resultado;
        } catch (PDOException $e) {
            echo ('Error al conectar ' . $e->getMessage());
        }
    }
}
