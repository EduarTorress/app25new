<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDOException;
use PDO;

class Vendedor extends Modelo
{
    var $txtnombre = "";

    function listar(string $abuscar)
    {
        try {
            $sql = 'call ProMuestraVendedores(:abuscar)';
            $query = $this->prepare($sql);
            $query->execute(['abuscar' => $abuscar]);
            $vendedores = $query->fetchAll(PDO::FETCH_ASSOC);
            return $vendedores;
        } catch (PDOException $e) {
            echo 'Hubo un error' . $e;
        }
    }

    function cargarvendedoresindex()
    {
        try {
            $sql = 'call ProMuestraVendedores(:abuscar)';
            $query = $this->prepare($sql);
            $query->execute(['abuscar' => '%%']);
            $vendedores = $query->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['vendedores'] = $vendedores;
            // return $query;
        } catch (PDOException $e) {
            echo 'Hubo un error' . $e;
        }
    }
    function buscarid($id)
    {
        $sql = "SELECT * FROM fe_vend WHERE idven=:id AND vend_acti='A'";
        $exec = $this->prepare($sql);
        $exec->execute(["id" => $id]);
        $resultado = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $resultado[0];
    }
    function save()
    {
        $sql = "INSERT INTO fe_vend (nomv) VALUES (:txtnombre)";
        $query = $this->prepare($sql);
        $query->execute([
            'txtnombre' => $this->txtnombre
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
    function update($id)
    {
        $sql = "UPDATE fe_vend SET nomv=:txtnombre WHERE idven=:idven";
        $query = $this->prepare($sql);
        $query->execute([
            'txtnombre' => $this->txtnombre,
            'idven' => $id
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
}
