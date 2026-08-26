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
    public function conectar(): PDO
    {
        if ($this->pdo === null) {

            $connection = "mysql:host={$this->host};dbname={$this->nombre_de_base};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => true,
            ];

            $this->pdo = new PDO(
                $connection,
                $this->usuario,
                $this->contrasena,
                $options
            );
        }

        return $this->pdo;
    }
    public function getData($sql)
    {
        $data = [];

        $pdo = $this->conectar();

        $result = $pdo->query($sql);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }
    public function numRows($sql)
    {
        $pdo = $this->conectar();

        $result = $pdo->query($sql);

        return $result->rowCount();
    }
    function getDataSingle($sql)
    {
        $pdo = $this->conectar();

        $result = $pdo->query($sql);

        $data = $result->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }
    function getDataSingleProp($sql, $prop)
    {
        $pdo = $this->conectar();

        $result = $pdo->query($sql);

        $data = $result->fetch(PDO::FETCH_ASSOC);

        return $data[$prop] ?? null;
    }
    public function execute(
        $query = '',
        $return_rows = 0,
        $array_valores = [],
        $array_tipos = []
    ) {
        $pdo = $this->conectar();

        $stmt = $pdo->prepare($query);

        foreach ($array_valores as $posicion => &$valor) {
            $tipo_var = ($array_tipos[$posicion] ?? 'STR') === 'STR'
                ? PDO::PARAM_STR
                : PDO::PARAM_INT;

            $stmt->bindParam(
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
    function executeInstruction($sql)
    {
        $pdo = $this->conectar();

        $result = $pdo->exec($sql);

        return $result !== false && $result > 0;
    }
    public function close(): void
    {
        $this->pdo = null;
    }
    function getLastId()
    {
        return $this->conectar()->lastInsertId();
    }
    public function startTransaction()
    {
        try {
            return $this->conectar()->beginTransaction();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function insertTransaction($sql, $data)
    {
        $pdo = $this->conectar();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        $this->lastinsertid = $pdo->lastInsertId();
    }
    public function submitTransaction()
    {
        try {
            $this->conectar()->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo !== null && $this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }
}
/*https://www.digitalocean.com/community/tutorials/how-to-use-the-pdo-php-extension-to-perform-mysql-transactions-in-php-on-ubuntu-18-04-es*/
/*https://es.stackoverflow.com/questions/8197/m%C3%A9todo-din%C3%A1mico-para-realizar-transacciones-de-actualizaci%C3%B3n-con-pdo*/