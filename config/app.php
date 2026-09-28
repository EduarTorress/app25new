<?php
$app = \Core\Foundation\Application::getInstance();

$datos = json_encode(array('empresa' => $app->empresa));
if (empty($app->empresa)) {
    return;
}

if (empty($_SESSION['db_config'])) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://companiasysven.com/otros.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $datos,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($httpcode <> 200) {
        return;
    }
    $odatac = json_decode($response);
    if ($odatac->server == 'No Encontrado') {
        return;
    }

    $_SESSION['db_config'] = [
        'ht' => $odatac->server,
        'dt' => $odatac->data,
        'us' => $odatac->usuario,
        'pw' => $odatac->pwd,
        'host' => $odatac->host,
        'entidad' => $odatac->entidad,
        'urlenvio' => $odatac->urlenvio,
        'urlconsulta' => $odatac->urlconsulta,
        'region' => $odatac->region,
        'empresa' => $app->empresa
    ];
}

$app->ht = $_SESSION['db_config']['ht'];
$app->dt = $_SESSION['db_config']['dt'];
$app->us = $_SESSION['db_config']['us'];
$app->pw = $_SESSION['db_config']['pw'];
$app->host = $_SESSION['db_config']['host'];
$app->entidad = $_SESSION['db_config']['entidad'];
$app->urlenvio = $_SESSION['db_config']['urlenvio'];
$app->urlconsulta = $_SESSION['db_config']['urlconsulta'];
$app->region = $_SESSION['db_config']['region'];
$app->dias = 0;
return [
    "database" => [
        'driver' => 'mysql',
        'host' => $app->ht,
        'database' => $app->dt,
        'username' => $app->us,
        'password' => $app->pw,
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
    ],
    "mail" => [],
];
