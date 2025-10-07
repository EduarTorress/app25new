<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class Bancos extends Modelo
{
    function listar($cbuscar)
    {
        $lista = array();
        $csql = "SELECT * FROM fe_bancos WHERE banc_acti='A'";
        $query = $this->prepare($csql);
        $query->execute();
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        return $lista;
    }
}
