<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class Dashboard extends Modelo
{
    function totalproductos()
    {
        try {
            $sql = "SELECT COUNT(*) FROM fe_art WHERE prod_acti='A'";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalclientes()
    {
        try {
            $sql = "SELECT COUNT(*) FROM fe_clie WHERE `clie_acti`='A'";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalventas()
    {
        try {
            $sql = "SELECT IF(COUNT(*) IS NULL,0,COUNT(*)) FROM fe_rcom WHERE acti='A' and idcliente>0 AND fech=CURRENT_DATE()";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function montoventassoles()
    {
        try {
            $sql = "SELECT IF(SUM(impo) IS NULL,0.00,SUM(impo)) FROM fe_rcom WHERE acti='A' and idcliente>0 AND fech=CURRENT_DATE() AND mone='S'";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function montoventasdolares()
    {
        try {
            $sql = "SELECT IF(SUM(impo) IS NULL,0.00,SUM(impo)) FROM fe_rcom WHERE acti='A' AND fech=CURRENT_DATE() AND mone='D'";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalpedidos()
    {
        try {
            $sql = "SELECT IF(COUNT(*) IS NULL,0,COUNT(*)) FROM fe_rped WHERE acti='A' AND fech=CURRENT_DATE()";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalventasporano()
    {
        try {
            $sql = "SELECT MONTHNAME(fech) AS mes,
            COUNT(*) AS total
            FROM fe_rcom 
            WHERE acti='A' AND idcliente>0 
            GROUP BY MONTH(fech)";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalpedidosporano()
    {
        try {
            $sql = "SELECT MONTHNAME(fech) AS mes,
            COUNT(*) AS total
            FROM fe_rped 
            WHERE acti='A' AND idclie>0 
            GROUP BY MONTH(fech)";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalmontoventas()
    {
        try {
            $sql = "SELECT YEAR(fech) AS ano,SUM(impo) AS total
            FROM fe_rcom 
            WHERE acti='A' AND idcliente>0 
            GROUP BY YEAR(fech)";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
}
