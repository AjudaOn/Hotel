<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/router.php';

error_log("=== INICIANDO APLICAÇÃO ===");

// Inicializar o router
$router = new App\Config\router();

// Rota de login
$router->add('/login', 'LoginController', 'login');
$router->add('/', 'LoginController', 'index');

// Rotas do admin
$router->add('/admin', 'AdminController', 'index');
$router->add('/views/dashboard', 'AdminController', 'dashboard');

// Rotas do usuário
$router->add('/user', 'UserController', 'index');
$router->add('/user/dashboard', 'UserController', 'dashboard');

// Pegar a URL atual
$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/Hotel', '', $url); // Remove /Hotel da URL
if ($url === '') {
    $url = '/';
}

error_log("URL a ser despachada: " . $url);
$router->dispatch($url);