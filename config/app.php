<?php
$app = \Core\Foundation\Application::getInstance();

$datos = json_encode(array('empresa' => $app->empresa));
if (empty($app->empresa)) {
    return;
}
if (empty($app->ht)) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://companiasysven.com/o.php',
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
    $servername = $odatac->server;
    $username = $odatac->usuario;
    $password = $odatac->pwd;
    $dbname = $odatac->data;
    $app->ht = $servername;
    $app->dt = $dbname;
    $app->pw = $password;
    $app->us = $username;
}
// $_ENV["DB_HOST"] = $odatac->server;
// $_ENV["DB_USER"] = $odatac->usuario;;
// $_ENV["DB_DATABASE"]  = $odatac->data;
// $_ENV["DB_PASSWORD"] = $odatac->pwd;
// $dotenv = \Dotenv\Dotenv::createImmutable($_ENV['DIR_ROOT']);
// $dotenv->load();
// return [
//     "database" => [
//         'driver' => $_ENV["DB_DRIVER"],
//         'host' => $_ENV["DB_HOST"],
//         'database' => $_ENV["DB_DATABASE"],
//         'username' => $_ENV["DB_USER"],
//         'password' => $_ENV["DB_PASSWORD"],
//         'charset' => 'utf8mb4',
//         'collation' => 'utf8mb4_unicode_ci',
//         'prefix' => '',
//     ],
//     "mail" => [],
// ];
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
