<?php
class Router {
    private $routes = [];
    private $basePath;

    public function __construct() {
        $this->basePath = '/Croiser2';
    }

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    private function matchRoute($method, $uri) {
        $uri = parse_url($uri, PHP_URL_PATH);

        if (strpos($uri, $this->basePath) === 0) {
            $uri = substr($uri, strlen($this->basePath));
        }

        $uri = trim($uri, '/');
        $uri = $uri ?: '/'; 

        foreach ($this->routes as $route) {

            if ($route['method'] !== $method) continue;

            $routePath = trim($route['path'], '/');
            $routePath = $routePath ?: '/'; 
            
            // convert the route path to regex pattern
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';


            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);   
                return [
                    'handler' => $route['handler'],
                    'params' => $matches
                ];
            }
        }

        return null;
    }

    public function dispatch($method, $uri) {
        $match = $this->matchRoute($method, $uri);
    
        if (!$match) {
            error_log("No route found for URI: $uri");
            $viewPath = __DIR__ . '/views/404.php';
            if (file_exists($viewPath)) {
                require $viewPath;
            } else {
                echo "404 - Page not found";
            }
            return;
        }
    
        [$controller, $action] = explode('@', $match['handler']);
        error_log("Matched route: Controller = $controller, Action = $action");
    
        if (!class_exists($controller)) { 
            error_log("Controller not found: $controller");
            echo "404 - Controller not found";
            return;
        }
    
        $controllerClass = new $controller();
        if (!method_exists($controllerClass, $action)) {
            error_log("Action not found: $action in $controller");
            echo "404 - Action not found";
            return;
        }
    
        $controllerClass->$action(...$match['params']);
    }
}