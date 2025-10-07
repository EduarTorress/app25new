<?php

namespace App\Models;

use App\Controllers\SerieController;
use PDO;
use PDOException;
use Core\Clases\conexion;
use Core\Routing\Modelo;

class GuiaRemitente extends Modelo
{
    public int $idauto = 0;
    public string $tdoc = "09";
    public string $cndoc = "";
    public $dfecha = "";
    public string $dfechat = "";
    public int $nidg = 0;
    public string $cptop = "";
    public string $cptoll = "";
    public string $cdctorelacionado = "";
    public string $tdocrel = "";
    public int $ntienda = 0;
    public string $cubigeo1 = " ";
    public string $cubigeo2 = " ";
    public int $nidv1 = 0;
    public int $nidv2 = 0;
    public int $nidr = 0;
    public int $nidd = 0;
    public int $nidtr = 0;
    public string $cdetalle = "";
    public string $conductor = "";
    public string $brevete = "";
    public string $placa1 = "";
    public string $constancia1 = "";
    public string $motivo = "V";
    public string $tipotransporte = "";
    public int $nidpr = 0;
    public string $idautov = "0"; // Para realizar canjes

    function Grabar($detalle)
    {
        $dfech = date("Y-m-d");
        try {
            $correlativo = SerieController::correlativo($_SESSION['nserie'], '09');
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];

            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $sqlrcom = "select FunIngresaCabeceraCV('09','E',:cndoc,:dfecha,:dfechar,'', 0,0,0,'','S',:ndolar,:nigv,'K',:codigo,'V',:nidus,1,1,0,0,0,0,0) as id";
            // IngresaResumenDcto('09','E',This.ndoc,This.fecha,This.fecha,"",0,0,0,'','S',fe_gene.dola,fe_gene.igv,'k',This.Codigo,'V',goapp.nidusua,1,goapp.tienda,0,0,0,0,0)
            $queryrcom = $pdo->prepare($sqlrcom);
            $queryrcom->execute([
                'cndoc' => $this->cndoc,
                'dfecha' => $dfech,
                'dfechar' => $dfech,
                'ndolar' => session()->get("gene_dola"),
                'nigv' => session()->get("gene_igv"),
                'codigo' => $this->nidd,
                'nidus' => session()->get('usuario_id')
            ]);

            if ($queryrcom->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $queryrcom->errorInfo(), "estado" => '0');
                return $rpta;
            }

            $queryrcom->setFetchMode(PDO::FETCH_ASSOC);
            $valorr = $queryrcom->fetch();
            // $connection->query("SELECT @parametroSalida")->fetch();
            $idrcom = $valorr['id'];

            $sqlig = "select FunIngresaGuias(:dfecha,:cptop,:cptoll,:dfechat,:nidus,:cdeta,:cndoc,
            :nidtda,:cubigeo1,:cubigeo2,:nidv1,:nidv2,:nidr,:nidd,:ctdocr,:cdctor,:cconductor,:cplaca1,:cconstancia1,:cbrevete,:nidtr,:tdoc,:motivo,:nidauto) as id ";

            $execig = $pdo->prepare($sqlig);
            $execig->execute([
                'dfecha' => $this->dfecha,
                'cptop' => $this->cptop,
                'cptoll' => $this->cptoll,
                'dfechat' => $this->dfechat,
                'nidus' => session()->get('usuario_id'),
                'cdeta' => $this->cdetalle,
                'cndoc' => $this->cndoc,
                'nidtda' => $this->ntienda,
                'cubigeo1' => $this->cubigeo1,
                'cubigeo2' => $this->cubigeo2,
                'nidv1' => $this->nidv1,
                'nidv2' => $this->nidv2,
                'nidr' => $this->nidr,
                'nidd' => $this->nidd,
                'ctdocr' => $this->tdocrel,
                'cdctor' => $this->cdctorelacionado,
                'cconductor' => $this->conductor,
                'cplaca1' => $this->placa1,
                'cconstancia1' => $this->constancia1,
                'cbrevete' => $this->brevete,
                'nidtr' => $this->nidtr,
                'tdoc' => $this->tdoc,
                'motivo' => $this->motivo,
                'nidauto' => $idrcom
            ]);

            if ($execig->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execig->errorInfo(), "estado" => '0');
                return $rpta;
            }

            $execig->setFetchMode(PDO::FETCH_ASSOC);
            $valor = $execig->fetch();

            $id = $valor['id'];

            $sqlkardex = "INSERT INTO fe_kar(idauto,idart,tipo,prec,cant,ttip,incl,alma,kar_epta,kar_unid,kar_equi)
            VALUES (:nid,:ncoda,'V',0,:ncant,'K','I',:alm,:epta,:kar_unid,:kar_equi)";

            $sqlguiase = "insert into fe_ent(entr_unid,entr_cant,entr_idar,entr_peso,entr_idgu,entr_idkar,entr_codi)values(:cunidad,:ncant,:codigo,:npeso,:nidg,:nidkar,:entr_codi)";

            $i = 0;
            $sw = 1; //MANEJO DE ERROR

            foreach ($detalle as $item) {
                $execk = $pdo->prepare($sqlkardex);
                $cant = floatval($item['cantidad']);
                $execk->execute([
                    "nid" => $idrcom,
                    "ncoda" => $item['coda'],
                    "ncant" => $cant,
                    "alm" => $_SESSION['idalmacen'],
                    "epta" => $item['presseleccionada'],
                    "kar_unid" => $item['unidad'],
                    "kar_equi" => $item['cantequi']
                ]);

                $nidkar = $pdo->lastInsertId();
                if ($execk->errorCode() != '00000') {
                    $sw = 0;
                    break;
                }

                if ($item['activo'] == 'A') {
                    $execguiase = $pdo->prepare($sqlguiase);
                    $ncant = floatval($item['cantidad']);
                    $npeso = floatval($item['peso']);
                    $unidad = floatval($item['unidad']);
                    $execguiase->execute([
                        "cunidad" => $unidad,
                        "ncant" => $ncant,
                        "nidg" => $id,
                        "npeso" => $npeso,
                        "codigo" => $item['coda'],
                        "entr_codi" => $item['scop'],
                        "nidkar" => $nidkar
                    ]);
                    if ($execguiase->errorCode() != '0000') {
                        $sw = 0;
                        break;
                    }
                }
                $i++;
            }

            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execguiase->errorInfo(),  "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $sqlus = 'update fe_serie set nume=nume+1 where idserie=:idserie';
            $execus = $pdo->prepare($sqlus);
            $execus->execute(["idserie" => $idserie]);
            if ($execus->errorCode() !== '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execus->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se Genero la Guia ", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function actualizar($detalle)
    {
        // ProActualizaCabeceraCV es para fe_rcom
        // ProActualizaGuiasVtas es para fe_guias

        // CREATE DEFINER=`syscom`@`%.%.%` PROCEDURE `ProActualizaCabeceraCV`(
        // ctdoc VARCHAR(2),cform CHAR,cndoc VARCHAR(12),dfecha DATE,dfechar DATE,cdetalle VARCHAR(120),
        // nv DECIMAL(12,2),nigv DECIMAL(12,2),nt DECIMAL(12,2),cndo2 VARCHAR(10),cm CHAR,
        // ndolar FLOAT,ni FLOAT,ctg CHAR,ccodp INTEGER,cmvto CHAR,nus INTEGER,opt INTEGER,nidcodt INTEGER,
        // n1 INTEGER,n2 INTEGER,n3 INTEGER,nitems INTEGER,npvta FLOAT,nidauto INTEGER)

        // This.ActualizaResumenDcto('09', 'E', This.ndoc, This.fecha, This.fecha, "", 0, 0, 0, "", 'S', fe_gene.dola,
        // fe_gene.igv, 'k', This.Codigo, 'V', goApp.nidusua, 1, goApp.Tienda, 0, 0, 0, 0, 0, This.nautor)

        $dfech = date("Y-m-d");
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            // $sqlcgv = "call ProActualizaCabeceraCV('09','E',:cndoc,:dfecha,:dfechar,:cdeta,0,0,0,'','S',:ndolar,
            // :ni,'IGV',:ccodp,'v',:nidus,1,'1',0,0,0,0,0,:nidauto);";

            // $st1 = $pdo->prepare($sqlcgv);
            // $st1->execute([
            //     'cndoc' => $this->cndoc,
            //     'dfecha' => $this->dfecha,
            //     'dfechar' => $dfech,
            //     'cdeta' => $this->cdetalle,
            //     'ndolar' => session()->get("gene_dola"),
            //     'ni' => session()->get("gene_igv"),
            //     'ccodp' => $this->nidd,
            //     'nidus' => session()->get('usuario_id'),
            //     'nidauto' => session()->get("idautov")
            // ]);

            // if ($st1->errorCode() != '0000') {
            //     $pdo->rollBack();
            //     $rpta = array('mensaje' => $st1->errorInfo(), "estado" => '0');
            //     return $rpta;
            // }

            // This.ActualizaGuiasVtas(This.fecha, This.ptop, This.ptoll, This.nautor, This.fechat, goApp.nidusua, This.detalle, 
            // This.Idtransportista, This.ndoc, This.idautog, goApp.Tienda, This.Codigo) 

            //CREATE DEFINER=`syscom`@`%.%.%` PROCEDURE `ProActualizaGuiasvtas`(dfecha DATE,cptop VARCHAR(150),cptoll VARCHAR(150),nidauto INTEGER,
            // dfechat DATE,nidus INTEGER,cdeta VARCHAR(150),nidtr INTEGER,cndoc VARCHAR(12),nidg INTEGER,nidtda INTEGER,nidcl INTEGER,cubigeo VARCHAR(8))

            $sqlcg = "call ProActualizaGuiasVtas(:dfecha,:ptop,:ptoll,:nidauto,:dfechat,:nidus,:cdeta,:idtrans,:ndoc,:nidautog,'1',:idclie,:ubig,:txtChoferVehiculo,:txtBrevete);";

            $st2 = $pdo->prepare($sqlcg);
            $st2->execute([
                'dfecha' => $this->dfecha,
                'ptop' => $this->cptop,
                'ptoll' => $this->cptoll,
                'nidauto' => session()->get("idautov"),
                'dfechat' => $this->dfechat,
                'nidus' => session()->get('usuario_id'),
                'cdeta' => $this->cdetalle,
                'idtrans' => $this->nidtr,
                'ndoc' => $this->cndoc,
                'nidautog' => session()->get("idautog"),
                'idclie' => $this->nidd,
                'ubig' => $this->cubigeo2,
                'txtChoferVehiculo' => $this->conductor,
                'txtBrevete' => $this->brevete,
            ]);

            if ($st2->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $st2->errorInfo(), "estado" => '0');
                return $rpta;
            }

            $sqldelk = "update fe_kar set acti='I' where idauto=:nidauto";
            $st3 = $pdo->prepare($sqldelk);
            $st3->execute([
                'nidauto' => session()->get("idautov")
            ]);

            if ($st3->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $st3->errorInfo(), "estado" => '0');
                return $rpta;
            }

            $sqldelent = "update fe_ent set entr_acti='I' where entr_idgu=:idautog";
            $st4 = $pdo->prepare($sqldelent);
            $st4->execute([
                'idautog' => session()->get("idautog")
            ]);

            if ($st4->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $st4->errorInfo(), "estado" => '0');
                return $rpta;
            }

            $sqlkardex = "INSERT INTO fe_kar(idauto,idart,tipo,prec,cant,ttip,incl,alma,kar_epta,kar_unid,kar_equi)
            VALUES (:nid,:ncoda,'V',0,:ncant,'K','I',:alm,:epta,:kar_unid,:kar_equi)";

            $sqlguiase = "insert into fe_ent(entr_unid,entr_cant,entr_idar,entr_peso,entr_idgu,entr_idkar,entr_codi)values(:cunidad,:ncant,:codigo,:npeso,:nidg,:nidkar,:entr_codi)";

            $i = 0;
            $sw = 1; //MANEJO DE ERROR
            foreach ($detalle as $item) {

                $cmbunidad = $item['unidad'];
                $cmbunidad = explode("-", $cmbunidad);
                $descunidad = $cmbunidad[0];
                $cantequi = $cmbunidad[1];
                $presseleccionada = $cmbunidad[2];

                $i++;
                $execkar = $pdo->prepare($sqlkardex);
                $cant = floatval($item['cantidad']);
                $execkar->execute([
                    "nid" => session()->get("idautov"),
                    "ncoda" => $item['codigo'],
                    "ncant" => $cant,
                    "alm" => $_SESSION['idalmacen'],
                    "epta" => $presseleccionada,
                    "kar_unid" => $descunidad,
                    "kar_equi" => $cantequi
                ]);

                $nidkar = $pdo->lastInsertId();

                if ($execkar->errorCode() != '00000') {
                    $sw = 0;
                    break;
                }

                $query = $pdo->prepare($sqlguiase);
                $ncant = floatval($item['cantidad']);
                $npeso = floatval($item['precio']);
                $query->execute([
                    "cunidad" => $descunidad,
                    "ncant" => $ncant,
                    "nidg" => session()->get("idautog"),
                    "npeso" => $npeso,
                    "codigo" => $item['codigo'],
                    "entr_codi" => $item['scop'],
                    "nidkar" => $nidkar
                ]);
                if ($query->errorCode() != '0000') {
                    $sw = 0;
                    break;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(),  "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se modifico la guia ", "ndoc" => $_SESSION['ndoc'], "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function grabarguiafromvta($detalle)
    {
        $dfech = date("Y-m-d");
        try {
            $correlativo = SerieController::correlativo($_SESSION['nserie'], '09');
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $sql = "select FunIngresaGuias(:dfecha,:cptop,:cptoll,:dfechat,:nidus,:cdeta,:cndoc,
            :nidtda,:cubigeo1,:cubigeo2,:nidv1,:nidv2,:nidr,:nidd,:ctdocr,:cdctor,:cconductor,:cplaca1,:cconstancia1,:cbrevete,:nidtr,:tdoc,:motivo,:nidauto) as id ";

            $exec = $pdo->prepare($sql);
            $exec->execute([
                'dfecha' => $this->dfecha,
                'cptop' => $this->cptop,
                'cptoll' => $this->cptoll,
                'dfechat' => $this->dfechat,
                'nidus' => session()->get('usuario_id'),
                'cdeta' => $this->cdetalle,
                'cndoc' => $this->cndoc,
                'nidtda' => $_SESSION['idalmacen'],
                'cubigeo1' => $this->cubigeo1,
                'cubigeo2' => $this->cubigeo2,
                'nidv1' => $this->nidv1,
                'nidv2' => $this->nidv2,
                'nidr' => $this->nidr,
                'nidd' => $this->nidd,
                'ctdocr' => $this->tdocrel,
                'cdctor' => $this->cdctorelacionado,
                'cconductor' => $this->conductor,
                'cplaca1' => $this->placa1,
                'cconstancia1' => $this->constancia1,
                'cbrevete' => $this->brevete,
                'nidtr' => $this->nidtr,
                'tdoc' => $this->tdoc,
                'motivo' => $this->motivo,
                'nidauto' => $this->idautov
            ]);

            if ($exec->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $exec->errorInfo(), "estado" => '0');
                return $rpta;
            }

            $exec->setFetchMode(PDO::FETCH_ASSOC);
            $valor = $exec->fetch();

            $id = $valor['id'];

            $sqlguiase = "INSERT INTO fe_ent(entr_idkar,entr_cant,entr_idgu,entr_idar,entr_unid,entr_peso,entr_codi) VALUES (:idkar,:cant,:idguia,:idart,:unid,:peso,:scop);";

            $sw = 1;
            foreach ($detalle as $item) {

                if ($item['activo'] == 'A') {
                    $cmbunidad = $item['unidad'];
                    $cmbunidad = explode("-", $cmbunidad);
                    $descunidad = $cmbunidad[0];
                    $cantequi = $cmbunidad[1];
                    $presseleccionada = $cmbunidad[2];
                    $execguiase = $pdo->prepare($sqlguiase);
                    $ncant = floatval($item['cantidad']);
                    $execguiase->execute([
                        "cant" => $ncant,
                        "idguia" => $id,
                        "idkar" => $item['nreg'],
                        "idart" => $item['coda'],
                        "unid" => $descunidad,
                        "peso" => $item['peso'],
                        "scop" => $item['scop']
                    ]);
                    if ($execguiase->errorCode() != '0000') {
                        $sw = 0;
                        break;
                    }
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execguiase->errorInfo(),  "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $sql = 'update fe_serie set nume=nume+1 where idserie=:idserie';
            $query = $pdo->prepare($sql);
            $query->execute(["idserie" => $idserie]);
            if ($query->errorCode() !== '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se genero la Guia ", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function listar()
    {
        try {
            $sql = "SELECT guia_idgui AS idauto,guia_fech AS fech,guia_fect AS fecht,c.idclie as idclie, c.razo AS dest,guia_ptop AS dirrem,
            guia_ptoll AS dirdes,guia_ndoc AS ndoc,v.nruc,c.ndni,guia_mens,
            CONCAT(v.nruc,'-09-',LEFT(guia_ndoc,4),'-',SUBSTR(guia_ndoc,5)) AS nombrexml
            FROM fe_guias g
            INNER JOIN fe_rcom AS r ON r.idauto=g.`guia_idau`
            INNER JOIN fe_clie c ON c.`idclie` = r.`idcliente`,
            fe_gene AS v
            WHERE guia_acti='A' order by fech desc";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function listarparacanje()
    {
        try {
            $sql = "SELECT guia_idgui AS idauto,guia_fech AS fech,guia_fect AS fecht,c.idclie as idclie, c.razo AS dest,guia_ptop AS dirrem,
            guia_ptoll AS dirdes,guia_ndoc AS ndoc,c.nruc,c.ndni,guia_mens,
            CONCAT(v.nruc,'-09-',LEFT(guia_ndoc,4),'-',SUBSTR(guia_ndoc,5)) AS nombrexml
            FROM fe_guias g
            INNER JOIN fe_rcom AS r ON r.idauto=g.`guia_idau`
            INNER JOIN fe_clie c ON c.`idclie` = r.`idcliente`,
            fe_gene AS v
            WHERE guia_acti='A' AND guia_moti='V' and tdoc='09' and guia_codt=:codt order by fech desc";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'codt' => $_SESSION['idalmacen']
            ]);
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function listarPorFechas($dfi, $dff, $cmbAlmacen)
    {
        try {
            $a = ($cmbAlmacen == '0') ? ' and guia_codt<>:cmbAlmacen  ' : ' and guia_codt=:cmbAlmacen ';
            $sql = "SELECT guia_idgui AS idauto,guia_fech AS fech,guia_fect AS fecht,c.idclie AS idclie, c.razo,guia_ptop AS dirrem,
            guia_ptoll AS dirdes,guia_ndoc AS ndoc,v.nruc,guia_mens,guia_moti,
            CONCAT(v.nruc,'-09-',LEFT(guia_ndoc,4),'-',SUBSTR(guia_ndoc,5)) AS nombrexml
            FROM fe_guias g
            INNER JOIN fe_rcom AS r ON r.idauto=g.`guia_idau`
            INNER JOIN fe_clie c ON c.`idclie` = r.`idcliente`,
            fe_gene AS v
            WHERE guia_acti='A' AND guia_fech between :dfi and :dff " . $a . "
            UNION ALL
            SELECT guia_idgui AS idauto,guia_fech AS fech,guia_fect AS fecht,p.idprov AS idclie,p.razo,guia_ptop AS dirrem,
            guia_ptoll AS dirdes,guia_ndoc AS ndoc,v.nruc,guia_mens,guia_moti,
            CONCAT(v.nruc,'-09-',LEFT(guia_ndoc,4),'-',SUBSTR(guia_ndoc,5)) AS nombrexml
            FROM fe_guias g
            INNER JOIN fe_prov AS p ON p.idprov=g.`guia_idpr`,
            fe_gene AS v
            WHERE guia_acti='A' AND guia_fech between :dfi and :dff " . $a . " ORDER BY fech,ndoc";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'cmbAlmacen' => $cmbAlmacen
            ]);
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function listarxenviar($dplaca)
    {
        try {
            $p = ($dplaca == 0) ? ' ' : ' and g.`guia_idv1`=:placa ';
            $sql = "SELECT guia_idgui as idauto,guia_fech AS fech,guia_fect AS fecht, c.razo AS dest,guia_ptop AS dirrem,
            p.`razo` as remi,guia_ptoll AS dirdes,guia_ndoc AS ndoc,v.nruc,
            concat(v.nruc,'-31-',left(guia_ndoc,4),'-',substr(guia_ndoc,5)) as nombrexml
            FROM fe_guias g
            INNER JOIN fe_clie c ON g.`guia_idde`=c.`idclie`
            INNER JOIN fe_prov p ON g.guia_idre=p.idprov,fe_gene as v
            WHERE guia_acti='A' and left(guia_mens,1)<>'0'" . $p;
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            if ($dplaca == 0) {
                $query->execute();
            } else {
                $query->execute([
                    'placa' => $dplaca
                ]);
            }
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function consultarGuiaDetalle($nidauto, $motivo = "", $tipo = 'M')
    {
        try {
            switch ($motivo) {
                case 'V':
                    $sql = "SELECT guia_ndoc AS ndoc,guia_fech AS fech,guia_fect AS fechat,guia_deta AS detalle,
                    a.descri,k.kar_unid AS unid,e.entr_cant AS cant,e.entr_peso AS peso,g.guia_ptop AS ptopartida,g.guia_ptoll AS ptollegada,
                    k.idart AS coda,e.entr_iden AS idem,k.idkar AS idkar,g.guia_idtr,k.codv,
                    IFNULL(placa,'') AS placa,IFNULL(t.razon,'') AS razont,guia_mens,guia_arch,k.prec,
                    IFNULL(t.ructr,'') AS ructr, IF(g.guia_cond IS NULL OR g.guia_cond='',t.nombr,g.guia_cond) AS conductor,
                    IFNULL(t.dirtr,'') AS direcciont, IF(g.guia_cond IS NULL OR g.guia_cond='',t.breve,g.guia_brev) AS brevete,
                    IFNULL(t.cons,'') AS constancia,IFNULL(t.marca,'') AS marca,c.nruc,c.ndni,
                    IFNULL(t.placa1,'') AS placa1,r.ndoc AS dcto,c.idclie,IFNULL(r.rcom_canj,'') as guia_idau,
                    c.razo,guia_idgui AS idgui,r.idauto,'09' AS tdoc,a.premen AS pre3,
                    guia_ptop AS ptop,v.ciudad,v.distrito,IFNULL(t.tran_tipo,'01') AS tran_tipo,
                    r.idauto AS idautov,guia_idgui AS idautog,guia_ubi1,guia_ubi2,e.`entr_codi`,a.tipro AS tipoproducto";
                    $sql .= ($tipo == 'M' ? ', k.kar_epta,k.kar_equi,pres_desc,epta_prec,epta_cant,epta_idep ' : ' ');
                    $sql .= "FROM  fe_guias AS g
                    INNER JOIN fe_ent AS e ON e.entr_idgu=g.guia_idgui
                    INNER JOIN fe_kar AS k ON k.`idkar`=e.`entr_idkar`
                    INNER JOIN fe_art AS a ON a.idart=k.idart
                    INNER JOIN fe_rcom AS r ON r.idauto=g.guia_idau
                    INNER JOIN fe_clie AS c ON c.idclie=r.idcliente";
                    $sql .= ($tipo == 'M' ? " LEFT JOIN fe_epta ep ON (k.idart=ep.epta_idar)
                    LEFT JOIN `fe_presentaciones` p ON (ep.epta_pres=p.pres_idpr)" : ' ');
                    $sql .= " INNER JOIN fe_tra AS t ON t.idtra=g.guia_idtr,fe_gene AS v
                    WHERE guia_idgui=:nidg AND entr_acti='A' AND k.acti='A'";
                    break;
                case 'C':
                    $sql = "SELECT guia_ndoc AS ndoc,guia_fech AS fech,guia_fect AS fechat,guia_deta AS detalle,
                    a.descri,e.entr_unid AS unid,e.entr_cant AS cant,e.entr_peso AS peso,g.guia_ptop AS ptopartida,g.guia_ptoll AS ptollegada,
                    a.idart AS coda,e.entr_iden AS idem,g.guia_idtr, IFNULL(placa,'') AS placa,IFNULL(t.razon,'') AS razont,
                    guia_mens,guia_arch,
                    IFNULL(t.ructr,'') AS ructr, IF(g.guia_cond IS NULL OR g.guia_cond='',t.nombr,g.guia_cond) AS conductor,
                    IFNULL(t.dirtr,'') AS direcciont, IF(g.guia_cond IS NULL OR g.guia_cond='',t.breve,g.guia_brev) AS brevete,
                    IFNULL(t.cons,'') AS constancia,IFNULL(t.marca,'') AS marca,c.nruc as rucproveedor,
                    IFNULL(t.placa1,'') AS placa1,g.guia_ndoc AS dcto,c.idprov,
                    c.razo as proveedor,guia_idgui AS idgui,'09' AS tdoc,v.nruc AS nruc,v.empresa as razo,
                    v.ciudad,v.distrito,IFNULL(t.tran_tipo,'01') AS tran_tipo,guia_dcto,
                    guia_idgui AS idautog,guia_ubi1,guia_ubi2,ubig as ubigprov,e.`entr_codi`,a.tipro as tipoproducto
                    FROM  fe_guias AS g
                    INNER JOIN fe_ent AS e ON e.entr_idgu=g.guia_idgui
                    INNER JOIN fe_art AS a ON a.idart=e.entr_idar
                    INNER JOIN fe_prov AS c ON c.idprov=g.guia_idpr
                    INNER JOIN fe_tra AS t ON t.idtra=g.guia_idtr,fe_gene AS v
                    WHERE guia_idgui=:nidg  AND entr_acti='A' AND e.entr_acti='A'";
                    break;
            }
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute(['nidg' => $nidauto]);
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function actualizarEstadoGuiaR($id)
    {
        $sql = "UPDATE fe_guias SET guia_mens='0 El comprobante fue aceptado' WHERE guia_idgui=:id";
        $query = $this->prepare($sql);
        $query->execute([
            "id" => $id
        ]);
        if ($query->errorCode() != '0000') {
            $rpta = array('mensaje' => $query->errorInfo(), "estado" => '0');
        } else {
            $rpta = array('mensaje' => "Se actualizo correctamente", "estado" => '1');
        }
        return $rpta;
    }
    function listarguiaresumen($num, $tdoc, $tipom)
    {
        if ($tdoc == '09') {
            if ($tipom == 'C') {
                $sql = "SELECT a.guia_idgui as idauto,a.guia_ndoc as ndoc,a.guia_fech as fech,'S' as mone,c.razo,0 as importe,guia_mens as rcom_mens,LEFT(guia_mens,1) AS estadoenviado,'09' as tdoc FROM
                fe_guias as a 
                inner JOIN fe_prov AS c ON(a.guia_idpr=c.idprov) WHERE a.guia_ndoc=:num and a.guia_acti='A'";
            } else {
                $sql = "SELECT a.guia_idgui as idauto,a.guia_ndoc as ndoc,a.guia_fech as fech,'S' as mone,c.razo,0 as importe,guia_mens as rcom_mens,LEFT(guia_mens,1) AS estadoenviado,'09' as tdoc FROM
                fe_guias as a 
                inner join fe_rcom AS b on b.idauto=a.guia_idau
                inner JOIN fe_clie AS c ON(b.idcliente=c.idclie) WHERE a.guia_ndoc=:num and a.guia_acti='A'";
            }
        } else {
            $sql = "SELECT a.guia_idgui as idauto,a.guia_ndoc as ndoc,a.guia_fech as fech,'S' as mone,c.razo,0 as importe,guia_mens as rcom_mens,LEFT(guia_mens,1) AS estadoenviado,'31' as tdoc FROM
            fe_guiastr as a 
            inner JOIN fe_prov AS c ON(a.guia_idre=c.idprov) WHERE a.guia_ndoc=:num and a.guia_acti='A'";
        }
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'num' => $num,
        ]);
        return $query;
    }
    function listarguiatraspaso($num)
    {
        $sql = "SELECT a.guia_idgui AS idauto,a.guia_ndoc AS ndoc,a.guia_fech AS fech,'S' AS mone,'TRASPASO' AS razo ,0 AS importe,guia_mens AS rcom_mens,LEFT(guia_mens,1) AS estadoenviado,'09' AS tdoc 
                FROM fe_guias AS a 
                INNER JOIN fe_rcom AS b ON b.idauto=a.guia_idau
                WHERE guia_ndoc=:num AND guia_acti='A'";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'num' => $num,
        ]);
        return $query;
    }
    function anularguia($id, $tdoc)
    {
        $p = ($tdoc == '09') ? ' ProAnulaEntregaFisica' : ' ProAnulaGuiaTransportista';
        try {
            $lsql = "CALL " . $p . "(:nauto,:nu)";
            $st = $this->prepare($lsql);
            $st->execute([
                'nauto' => $id,
                'nu' => session()->get('usuario_id')
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
