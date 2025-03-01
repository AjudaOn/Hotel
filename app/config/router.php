<?php
namespace App\Config;

class Router {
    private $routes = [];

    public function add($url, $controller, $method) {
        $this->routes[$url] = ['controller' => $controller, 'method' => $method];
    }

    public function get($url, $controllerMethod) {
        list($controller, $method) = explode('@', $controllerMethod);
        $this->routes[$url] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch($url) {
        // Verificar se a URL contém parâmetros (como {uf_id})
        foreach ($this->routes as $route => $handler) {
            // Converter o padrão de rota em uma expressão regular
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
            $pattern = '@^' . $pattern . '$@';
            
            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches); // Remove a correspondência completa
                
                $controller = $handler['controller'];
                $method = $handler['method'];
                
                // Adicionar o namespace completo aos controllers
                $controllerClass = "\\App\\Controllers\\{$controller}";
                
                $controllerInstance = new $controllerClass();
                
                // Chamar o método com os parâmetros extraídos da URL
                call_user_func_array([$controllerInstance, $method], $matches);
                return;
            }
        }
        
        // Se nenhuma rota corresponder
        if (array_key_exists($url, $this->routes)) {
            $controller = $this->routes[$url]['controller'];
            $method = $this->routes[$url]['method'];
            
            // Adicionar o namespace completo aos controllers
            $controllerClass = "\\App\\Controllers\\{$controller}";
            $controllerInstance = new $controllerClass();
            $controllerInstance->$method();
        } else {
            header("Location: /Hotel/login");
        }
    }
}

$router = new Router();

// Rota de login
$router->add('/login', 'LoginController', 'login');

// Rotas do admin
$router->add('/admin', 'AdminController', 'index');
$router->add('/views/dashboard', 'AdminController', 'dashboard');
$router->add('/admin/reserva', 'AdminController', 'reserva');
$router->add('/admin/salvar-reserva', 'AdminController', 'salvarReserva');
$router->add('/admin/listar', 'AdminController', 'listarReservas'); // Nova rota

// Rota para buscar cidades por UF
$router->add('/admin/get-cidades-by-uf/{uf_id}', 'AdminController', 'getCidadesByUf');

// Rotas do usuário
$router->add('/user', 'UserController', 'index');
$router->add('/user/dashboard', 'UserController', 'dashboard');

// Pegar a URL atual
$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/Hotel', '', $url); // Remove /Hotel da URL
$router->dispatch($url);