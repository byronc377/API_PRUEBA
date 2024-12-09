<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: http://localhost:3000'); // Permitir solicitudes desde React
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__.'/core/Router.php';
require_once __DIR__.'/core/Request.php';


spl_autoload_register(function ($class) {
    $controllerPath = __DIR__."/app/Controllers/$class.php";
    $modelPath = __DIR__."/app/Models/$class.php";

    if (file_exists($controllerPath)) {
        require_once $controllerPath;
    } elseif (file_exists($modelPath)) {
        require_once $modelPath;
    }
});

// Crear instancia del enrutador
$router = new Router(new Request);

//$router->get('/Login', 'AuthController@login');

$router->post('/Login', 'AuthController@login');
$router->post('/InsertNuevoCliente', 'ClienteController@insertNuevoCliente');
$router->post('/getCliente', 'ClienteController@getCliente');
$router->post('/insertDetalleCliente', 'DetalleClienteController@insertDetalleCliente');
$router->post('/updateDetalleCliente', 'DetalleClienteController@updateDetalle');
$router->post('/logCliente', 'LogController@consultarLog');
$router->post('/updateEstado', 'DetalleClienteController@updateEstadoDetalle');
$router->post('/getClienteByFechaCreacion', 'ClienteController@getClienteByFechaCreacion');



// Resolver la ruta actual
$router->resolve();
