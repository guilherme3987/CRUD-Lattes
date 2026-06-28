<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';
use App\Core\Router;
use App\Controllers\PesquisadorController;

$router = new Router();
$controller = new PesquisadorController();

$router->add('default', function () use ($controller) {
    $controller->index();
});
$router->add('profile', function () use ($controller) {
    $controller->profile();
});
$router->add('login', function () use ($controller) {
    $controller->login();
});
$router->add('logout', function () use ($controller) {
    $controller->logout();
});
$router->add('search', function () use ($controller) {
    $controller->search();
});
$router->add('show', function (?string $id) use ($controller) {
    $controller->show($id);
});
$router->add('list', function () use ($controller) {
    $controller->list();
});
$router->add('create', function () use ($controller) {
    $controller->create();
});
$router->add('edit', function () use ($controller) {
    $controller->edit();
});
$router->add('delete', function () use ($controller) {
    $controller->delete();
});
$router->add('addAtuacao', function () use ($controller) {
    $controller->addAtuacao();
});
$router->add('deleteAtuacao', function (?string $id) use ($controller) {
    $controller->deleteAtuacao((int)$id);
});
$router->add('addFormacao', function () use ($controller) {
    $controller->addFormacao();
});
$router->add('deleteFormacao', function (?string $id) use ($controller) {
    $controller->deleteFormacao((int)$id);
});
$router->add('updateFormacao', function (?string $id) use ($controller) {
    $controller->updateFormacao((int)$id);
});
$router->add('updateAtuacao', function (?string $id) use ($controller) {
    $controller->updateAtuacao((int)$id);
});

$action = $_GET['action'] ?? 'default';
$id = $_GET['id'] ?? null;

$router->dispatch($action, $id);
