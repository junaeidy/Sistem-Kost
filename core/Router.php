<?php

namespace Core;

/**
 * Router Class
 * Handles URL routing and dispatching
 */
class Router
{
    private $routes = [];
    private $middlewares = [];
    private $currentMiddlewares = [];

    /**
     * Add GET route
     * 
     * @param string $path
     * @param string|callable $handler
     * @return self
     */
    public function get($path, $handler)
    {
        return $this->addRoute('GET', $path, $handler);
    }

    /**
     * Add POST route
     * 
     * @param string $path
     * @param string|callable $handler
     * @return self
     */
    public function post($path, $handler)
    {
        return $this->addRoute('POST', $path, $handler);
    }

    /**
     * Add PUT route
     * 
     * @param string $path
     * @param string|callable $handler
     * @return self
     */
    public function put($path, $handler)
    {
        return $this->addRoute('PUT', $path, $handler);
    }

    /**
     * Add DELETE route
     * 
     * @param string $path
     * @param string|callable $handler
     * @return self
     */
    public function delete($path, $handler)
    {
        return $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * Add route with any method
     * 
     * @param string $method
     * @param string $path
     * @param string|callable $handler
     * @return self
     */
    private function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middlewares' => $this->currentMiddlewares
        ];
        
        // Reset current middlewares
        $this->currentMiddlewares = [];
        
        return $this;
    }

    /**
     * Add middleware to next route
     * 
     * @param string|array $middleware
     * @return self
     */
    public function middleware($middleware)
    {
        $middlewares = is_array($middleware) ? $middleware : [$middleware];
        $this->currentMiddlewares = array_merge($this->currentMiddlewares, $middlewares);
        return $this;
    }

    /**
     * Register middleware class
     * 
     * @param string $name
     * @param string $class
     */
    public function registerMiddleware($name, $class)
    {
        $this->middlewares[$name] = $class;
    }

    /**
     * Dispatch the request
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($requestUri, '?')) !== false) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        
        // Remove base path if running in subdirectory
        $basePath = $this->getBasePath();
        if ($basePath && strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
        
        $requestUri = $requestUri ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->convertToRegex($route['path']);
            
            if (preg_match($pattern, $requestUri, $matches)) {
                // Remove full match
                array_shift($matches);
                
                // Execute middlewares
                if (!$this->executeMiddlewares($route['middlewares'])) {
                    return;
                }
                
                // Execute handler
                $this->executeHandler($route['handler'], $matches);
                return;
            }
        }

        // No route found
        $this->handleNotFound();
    }

    /**
     * Convert route path to regex pattern
     * 
     * @param string $path
     * @return string
     */
    private function convertToRegex($path)
    {
        // Replace :param with named capture group
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        
        // Escape forward slashes
        $pattern = str_replace('/', '\/', $pattern);
        
        return '/^' . $pattern . '$/';
    }

    /**
     * Execute middlewares
     * 
     * @param array $middlewareNames
     * @return bool
     */
    private function executeMiddlewares($middlewareNames)
    {
        foreach ($middlewareNames as $name) {
            if (!isset($this->middlewares[$name])) {
                continue;
            }

            $middlewareClass = $this->middlewares[$name];
            $middleware = new $middlewareClass();

            if (method_exists($middleware, 'handle')) {
                $result = $middleware->handle();
                if ($result === false) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Execute route handler
     * 
     * @param string|callable $handler
     * @param array $params
     */
    private function executeHandler($handler, $params = [])
    {
        // Ensure params is indexed array (not associative)
        $params = array_values($params);
        
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
        } elseif (is_string($handler)) {
            // Parse Controller@method syntax
            list($controllerName, $method) = explode('@', $handler);
            
            // Build full controller class name
            $controllerClass = "App\\Controllers\\{$controllerName}";
            
            if (!class_exists($controllerClass)) {
                die("Controller not found: {$controllerClass}");
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $method)) {
                die("Method not found: {$method} in {$controllerClass}");
            }

            call_user_func_array([$controller, $method], $params);
        }
    }

    /**
     * Get base path from public folder
     * 
     * @return string
     */
    private function getBasePath()
    {
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        return $scriptName === '/' ? '' : $scriptName;
    }

    /**
     * Handle 404 Not Found
     */
    private function handleNotFound()
    {
        http_response_code(404);
        
        $viewFile = dirname(__DIR__) . '/resources/views/errors/404.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "<h1>404 - Page Not Found</h1>";
        }
        exit;
    }

    /**
     * Get current URL
     * 
     * @return string
     */
    public static function getCurrentUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Build URL from path
     * 
     * @param string $path
     * @return string
     */
    public static function url($path = '')
    {
        $appConfig = require dirname(__DIR__) . '/config/app.php';
        $baseUrl = rtrim($appConfig['url'], '/');
        $path = ltrim($path, '/');
        return $baseUrl . '/' . $path;
    }
}
