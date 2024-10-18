<?php

use App\Controllers\MigrationController;
use App\Core\Factory;
use App\Core\Router;
use App\Controllers\MainController;

if (!isset($pdo)) {
    $pdo = Factory::createDatabaseConnection();
}

if (!isset($router)) {
    $router = new Router();
}
$router->post('/sorteio/criar', function () use ($pdo) {
    $controller = Factory::createDrawController($pdo);
    $controller->createDraw();
});

$router->post('/sorteio/{id}/resultado', function ($id) use ($pdo) {
    $controller = Factory::createDrawController($pdo);
    $controller->generateDrawResult($id);
});

$router->get('/sorteio/{id}/resultado', function ($id) use ($pdo) {
    $controller = Factory::createDrawController($pdo);
    $controller->render($id);
});

$router->post('/bilhete/criar', function () use ($pdo) {
    $controller = Factory::createTicketController($pdo);
    $controller->generateTickets();
});

$router->get('/migrate', function () use ($pdo){
    (new MigrationController($pdo))->runMigrations();
});

$router->get('/', function () {
    (new MainController())->render();
});

$router->get('/server', function () {
    (new MainController())->renderJson();
});

$router->resolve();
