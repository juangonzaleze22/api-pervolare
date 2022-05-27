<?php
// Create and configure Slim app
$config = ['settings' => [
    'addContentLengthHeader' => false,
    'displayErrorDetails' => true,
]];

require __DIR__ . '/vendor/autoload.php';
require "config/db.php";
require "config/jwt.php";
/* require "config/jwt.php"; */

$app = new \Slim\App($config);


/* RUTAS DE ARCHIVOS */
require "src/routes/login.php";
require "src/routes/users.php";
require "src/routes/categories.php";

/* cors */
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Run app
$app->run();