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

    private $pdo = null;

    public $lastinsertid;

    public function __construct()
    {
        $config = require $_ENV['DIR_ROOT'] . "/config/app.php";

        $this->nombre_de_base = $config['database']['database'];
        $this->host           = $config['database']['host'];
        $this->usuario       = $config['database']['username'];
        $this->contrasena    = $config['database']['password'];
    }

    public function conectar()
    {
        try {
            // Si ya existe una conexión, reutilizarla
            if ($this->pdo instanceof PDO) {
                return $this->pdo;
            }

            $connection =
                $this->tipo_de_base .
                ":host=" . $this->host .
                ";dbname=" . $this->nombre_de_base .
                ";charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            $this->pdo = new PDO(
                $connection,
                $this->usuario,
                $this->contrasena,
                $options
            );

            return $this->pdo;
        } catch (PDOException $e) {

            throw new Exception(
                'Error de conexión con la base de datos: ' .
                    $e->getMessage()
            );
        }
    }

    public function getData($sql)
    {
        $stmt = $this->conectar()->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDataSingle($sql)
    {
        $stmt = $this->conectar()->query($sql);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }

    public function getDataSingleProp($sql, $prop)
    {
        $data = $this->getDataSingle($sql);

        return $data[$prop] ?? null;
    }

    public function numRows($sql)
    {
        $stmt = $this->conectar()->query($sql);

        return $stmt->rowCount();
    }

    public function execute(
        $query = '',
        $return_rows = 0,
        $array_valores = [],
        $array_tipos = []
    ) {
        $stmt = $this->conectar()->prepare($query);

        foreach ($array_valores as $posicion => &$valor) {

            $tipo_var =
                isset($array_tipos[$posicion]) &&
                $array_tipos[$posicion] === 'STR'
                ? PDO::PARAM_STR
                : PDO::PARAM_INT;

            $stmt->bindValue(
                $posicion + 1,
                $valor,
                $tipo_var
            );
        }

        $result = $stmt->execute();

        if ($return_rows > 0 && $result) {

            return $return_rows == 2
                ? $stmt->fetch(PDO::FETCH_ASSOC)
                : $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function executeInstruction($sql)
    {
        $stmt = $this->conectar()->query($sql);

        return $stmt->rowCount() > 0;
    }

    public function getLastId()
    {
        return $this->conectar()->lastInsertId();
    }

    public function startTransaction()
    {
        try {

            $pdo = $this->conectar();

            if (!$pdo->inTransaction()) {
                $pdo->beginTransaction();
            }

            return true;
        } catch (PDOException $e) {

            return false;
        }
    }

    public function insertTransaction($sql, $data)
    {
        $stmt = $this->conectar()->prepare($sql);

        $stmt->execute($data);

        $this->lastinsertid =
            $this->conectar()->lastInsertId();

        return true;
    }

    public function submitTransaction()
    {
        try {

            if ($this->conectar()->inTransaction()) {
                $this->conectar()->commit();
            }

            return true;
        } catch (PDOException $e) {

            if ($this->conectar()->inTransaction()) {
                $this->conectar()->rollBack();
            }

            return false;
        }
    }

    public function close()
    {
        $this->pdo = null;
    }
}
