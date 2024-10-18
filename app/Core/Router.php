<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function get($route, $callback)
    {
        $this->routes['GET'][$route] = $callback;
    }

    public function post($route, $callback)
    {
        $this->routes['POST'][$route] = $callback;
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if($path === "") return;

        try {
            foreach ($this->routes[$method] as $route => $callback) {

                if ($route === $path) {
                    return call_user_func($callback); // Chama o callback diretamente
                }

                $routePattern = preg_replace('/\{[a-zA-Z]+\}/', '([a-zA-Z0-9-_]+)', $route);
                $routePattern = "#^" . $routePattern . "$#";

                if (preg_match($routePattern, $path, $matches)) {
                    array_shift($matches);
                    return call_user_func_array($callback, $matches);
                }
            }


            throw new HttpException(404, "Rota não encontrada");
        } catch (HttpException $e) {
            http_response_code($e->getStatusCode());
            echo json_encode([
                'error' => $e->getMessage()
            ], JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Erro Interno no Servidor',
                'details' => $e->getMessage()
            ], JSON_THROW_ON_ERROR);
        }
    }
}