<?php

use Controllers\LabCollectorController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use Controllers\OpenApiController;

require __DIR__ . '/vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$app = AppFactory::create();
// $app->setBasePath("/labCollectorJA/libs/swagger/swaggergenerator"); 
$app->setBasePath("/labCollectorJA/libs/swagger/swaggergenerator"); 
$app->addBodyParsingMiddleware();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});


$app->run();