<?php

namespace App\Models;

use App\Controllers\SerieController;
use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Combo extends Modelo
{
    var $idproducto = "";
    var $costo = 0.00;

    function save($detalle)
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $sqlclearcombo = "update fe_epta set epta_acti='I' where epta_coda=:idproducto";
            $execclearcombo = $pdo->prepare($sqlclearcombo);
            $execclearcombo->execute([
                'idproducto' => $this->idproducto
            ]);

            if ($execclearcombo->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execclearcombo->errorInfo(),  "ndoc" => "", "estado" => '0');
                return $rpta;
            }

             $correlativo = SerieController::correlativo($_SESSION['nserie'], 'CP');
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }

            $sqlIC = "SELECT FunIngresaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfechar,:cdetalle,:nv,:nigv,:nt,:cndo2,:cm,
                :ndolar,:ni,:ctg,:ccodp,:cmvto,:nus,:opt,:nidcodt,:n1,:n2,:n3,:nitem,:npvta,0) AS id";
            $exeIC = $pdo->prepare($sqlIC);
            $exeIC->execute([
                'ctdoc' => 'CP',
                'cform' => 'E',
                'cndoc' => $correlativo[0]['correlativo'],
                'dfecha' =>date('Y-m-d'),
                'dfechar' =>date('Y-m-d'),
                'cdetalle' => 'Armo combos',
                'nv' => 0,
                'nigv' => 0,
                'nt' => 0,
                'cndo2' => '',
                'cm' => 'S',
                'ndolar' =>0,
                'ni' => 0,
                'ctg' => 'K',
                'ccodp' => 0,
                'cmvto' => 'V',
                'nus' =>  session()->get("usuario_id"),
                'opt' => 0,
                'nidcodt' =>$_SESSION['idalmacen'],
                'n1' => 0,
                'n2' => 0,
                'n3' => 0,
                'nitem' => 0,
                'npvta' => 0
            ]);
            $id = $exeIC->fetchColumn();
           
            foreach ($detalle as $d) {
                $sql = "INSERT INTO fe_epta (epta_coda,epta_idar,epta_cost,epta_codt,epta_cant) VALUES(:idproducto,:idarts,:costo,:codigotienda,1)";
                $exec = $pdo->prepare($sql);
                $exec->execute([
                    'idproducto' => $this->idproducto,
                    'idarts' => $d['idart'],
                    'costo' => $d['costo'],
                    'codigotienda' => $_SESSION['idalmacen']
                ]);
                // $this->costo = $this->costo + $d['costo'];

                $sqlIK = "SELECT FunIngresaKardex1(:idauto,:cc,:ctipo,:npr,:nct,:cincl,:tmvto,:ccdov,:calma,:nidcosto1,:vcom,0,'UNIDAD',1,0,'','2000-01-01') AS NID";
                $exeIK = $pdo->prepare($sqlIK);
                $exeIK->execute([
                    'idauto' => $id,
                    'cc' => $d['idart'],
                    'ctipo' => 'V',
                    'npr' => '0',
                    'nct' => '1',
                    'cincl' => 'I',
                    'tmvto' => 'K',
                    'ccdov' => '0',
                    'calma' => $_SESSION['idalmacen'],
                    'nidcosto1' => '0',
                    'vcom' => '0'
                ]);
            }

            // $sqlupdateart="update fe_art set cost=:costo,prec=:costo,premay=:costo,premen=:costo,pre3=:costo where idart=:idart";
            // $execupdateart = $pdo->prepare($sqlupdateart);
            // $execupdateart->execute([
            //     'idart' => $this->idproducto,
            //     'costo' => $this->costo
            // ]);

            // if ($execupdateart->errorCode() != '00000') {
            //     $pdo->rollBack();
            //     $rpta = array('mensaje' => $execupdateart->errorInfo(),  "ndoc" => "", "estado" => '0');
            //     return $rpta;
            // }

            if ($exec->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $exec->errorInfo(),  "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se generó el combo correctamente ", "ndoc" => '', "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function buscarid()
    {
        $sql = "SELECT c.*,a.descri
                FROM fe_epta c 
                INNER JOIN fe_art a ON c.epta_idar=a.idart 
                WHERE epta_coda=:id AND epta_acti='A'";
        $query = $this->prepare($sql);
        $query->execute(["id" => $this->idproducto]);
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
}
