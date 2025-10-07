<?php

namespace Core\Routing;

use Core\Clases\conexion;
use PDO;
use PDOException;

class Modelo
{
    private conexion $db;
    function __construct()
    {
        $this->db = new conexion();
    }
    function query($query)
    {
        return $this->db->conectar()->prepare($query);
    }
    function prepare($prepare)
    {
        return $this->db->conectar()->prepare($prepare);
    }
    function tipocambio()
    {
        $csql = "select dola from fe_gene where idgene=1";
        $ncon = $this->db->conectar();
        $query = $ncon->prepare($csql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $this->mdolar = $result->dola;
            }
        }
        return $this->mdolar;
    }
    function buscardato($datos)
    {
        $vdvto = 0;
        switch ($datos['tabla']) {
            case 'fe_grupo':
                $centidad = 'Grupos';
                break;
            case 'fe_mar':
                $centidad = 'Marcas';
                break;
            case 'fe_cat':
                $centidad = 'Lineas';
                break;
            case 'fe_fletes':
                $centidad = 'Fletes';
                break;
        }
        $csql = 'select FunBuscaNombre(:centidad,:cvalor,:nid) as vdvto';
        $query = $this->prepare($csql);
        $query->execute([
            "centidad" => $centidad,
            "cvalor" => $datos['valor'],
            "nid" => $datos['id']
        ]);
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $vdvto = $result->vdvto;
            }
        }
        if ($vdvto > 0) {
            return true;
        } else {
            return false;
        }
    }
    function datosempresa()
    {
        try {
            $sql = "SELECT * FROM fe_gene where idgene=1";
            $ncon = $this->db->conectar();
            $query = $ncon->prepare($sql);
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $rs = $query->fetchAll();
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al Conectar' . $e->getMessage());
        }
    }
    function cargarsucursalesindex()
    {
        try {
            $sql = "SELECT * FROM fe_sucu where dire<>''";
            $ncon = $this->db->conectar();
            $query = $ncon->prepare($sql);
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $rs = $query->fetchAll();
            $_SESSION['sucursales'] = $rs;
        } catch (PDOException $e) {
            echo ('Error al Conectar' . $e->getMessage());
        }
    }
    function cargarsucursales()
    {
        try {
            return $_SESSION['sucursales'];
        } catch (PDOException $e) {
            echo ('Error al Conectar' . $e->getMessage());
        }
    }
}
