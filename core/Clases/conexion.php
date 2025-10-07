<?php

namespace Core\Clases;

use Exception;
use PDO;
use PDOException;

class conexion
{
    private $tipo_de_base = 'mysql';
    private $host = '';
    private $nombre_de_base = '';
    private $usuario = '';
    private $contrasena = '';
    private $conexion;
    private $empresa;
    public $lastinsertid;
    protected $pdo;
    public function __construct()
    {
        $config = require $_ENV['DIR_ROOT'] . "/config/app.php";
        $this->nombre_de_base = $config['database']['database'];
        $this->host = $config['database']['host'];
        $this->usuario = $config['database']['username'];
        $this->contrasena = $config['database']['password'];
    }
    public function conectar()
    {
        try {
            $connection = $this->tipo_de_base . ":host=" . $this->host . ";dbname=" . $this->nombre_de_base . ";charset=utf8";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->pdo = new PDO($connection, $this->usuario, $this->contrasena);
            return $this->pdo;
        } catch (PDOException $e) {
            print_r('Error de Conexión con la base de Datos');
        }
    }
    public function getData($sql)
    {

        $data = array();
        $result = $this->conexion->query($sql);

        $error = $this->conexion->errorInfo();
        if ($error[0] === "00000") {
            $result->execute();
            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    array_push($data, $row);
                }
            }
        } else {
            throw new Exception($error[2]);
        }
        return $data;
    }
    public function numRows($sql)
    {
        $result = $this->conexion->query($sql);
        $error = $this->conexion->errorInfo();

        if ($error[0] === "00000") {
            $result->execute();
            return $result->rowCount();
        } else {
            throw new Exception($error[2]);
        }
    }
    function getDataSingle($sql)
    {

        $result = $this->conexion->query($sql);

        $error = $this->conexion->errorInfo();

        if ($error[0] === "00000") {
            $result->execute();
            if ($result->rowCount() > 0) {
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        } else {
            throw new Exception($error[2]);
        }
        return null;
    }


    function getDataSingleProp($sql, $prop)
    {

        $result = $this->conexion->query($sql);
        $error = $this->conexion->errorInfo();

        if ($error[0] === "00000") {
            $result->execute();
            if ($result->rowCount() > 0) {
                $data = $result->fetch(PDO::FETCH_ASSOC);
                return $data[$prop];
            }
        } else {
            throw new Exception($error[2]);
        }
        return null;
    }
    function execute($query = '', $return_rows = 0, $array_valores = array(), $array_tipos = array())
    {
        $this->_pdoStat = $this->_pdo->prepare($query);
        foreach ($array_valores as $posicion => &$valor) {
            $tipo_var = 'STR' == $array_tipos[$posicion] ? PDO::PARAM_STR : PDO::PARAM_INT;
            $this->_pdoStat->bindParam($posicion + 1, $valor, $tipo_var);
        }
        $result = $this->_pdoStat->execute();
        if (0 < $return_rows && $result) {
            return $return_rows == 2 ? $this->_pdoStat->fetch() : $this->_pdoStat->fetchAll();
        }
        return $result;
    }
    function executeInstruction($sql)
    {

        $result = $this->conexion->query($sql);
        $error = $this->conexion->errorInfo();

        if ($error[0] === "00000") {
            $result->execute();
            return $result->rowCount() > 0;
        } else {
            throw new Exception($error[2]);
        }
    }

    function close()
    {
        $this->pdo = null;
    }

    function getLastId()
    {
        return $this->conexion->lastInsertId();
    }
    public function startTransaction()
    {
        $this->conectar()->beginTransaction();
        if ($this->conectar()->errorCode() != '00000') {
            return false;
        }
        return true;
    }

    public function insertTransaction($sql, $data)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        $this->lastinsertid = $this->pdo->lastInsertId();
    }

    public function submitTransaction()
    {
        try {
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }

        return true;
    }
}
/*https://www.digitalocean.com/community/tutorials/how-to-use-the-pdo-php-extension-to-perform-mysql-transactions-in-php-on-ubuntu-18-04-es*/
/*https://es.stackoverflow.com/questions/8197/m%C3%A9todo-din%C3%A1mico-para-realizar-transacciones-de-actualizaci%C3%B3n-con-pdo*/