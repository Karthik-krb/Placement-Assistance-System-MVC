<?php
// app/core/App.php
declare(strict_types=1);

/**
 * Small application dispatcher / router.
 * - Uses $config['basePath'] if available to strip a base folder from the request URI.
 * - Dispatches a few hard-coded routes (home + 3 auth routes).
 * - Falls back to a controller/method convention: /controller/method/param1/param2
 *
 * Usage: new App($config); $app->run();
 */
class App
{
    private array $config;
    private string $basePath;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->basePath = rtrim($config['basePath'] ?? '', '/');
    }

    public function run(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Remove basePath if set (e.g., '/PAS/public')
        if ($this->basePath !== '' && str_starts_with($uri, $this->basePath)) {
            $uri = substr($uri, strlen($this->basePath));
            if ($uri === '') $uri = '/';
        }

        // normalize trailing slash
        $path = rtrim($uri, '/');
        if ($path === '') $path = '/';

        // ROUTES: adjust these if you want different URIs
        $routes = [
            // home
            ['GET', '/', 'HomeController@index'],

            // auth forms and submissions
            ['GET',  '/auth/admin/login',      'AdminAuthController@showLogin'],
            ['POST', '/auth/admin/login',      'AdminAuthController@login'],

            ['GET',  '/auth/candidate/login',  'CandidateAuthController@showLogin'],
            ['POST', '/auth/candidate/login',  'CandidateAuthController@login'],
            ['GET',  '/auth/candidate/register', 'CandidateAuthController@showRegister'],
            ['POST', '/auth/candidate/register', 'CandidateAuthController@register'],

            ['GET',  '/auth/company/login',    'CompanyAuthController@showLogin'],
            ['POST', '/auth/company/login',    'CompanyAuthController@login'],
        ];

        // try to match route
        foreach ($routes as $r) {
            [$allowedMethod, $routePath, $handler] = $r;
            if ($method !== $allowedMethod) continue;
            if ($routePath === rtrim($path, '/')) {
                $this->invokeHandler($handler);
                return;
            }
        }

        // fallback — convention: /controller/method/arg1/arg2
        $this->dispatchByConvention($path, $method);
    }

    private function invokeHandler(string $handler): void
    {
        [$controllerName, $action] = explode('@', $handler);
        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo "{$controllerName} not found. Create app/Controllers/{$controllerName}.php";
            exit;
        }
        $controller = new $controllerName($this->config);
        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo "Method {$action} not found on controller {$controllerName}";
            exit;
        }
        // call the action; allow controller to handle outputs/redirects
        $controller->{$action}();
    }

    private function dispatchByConvention(string $path, string $method): void
    {
        // remove leading slash
        $path = ltrim($path, '/');
        if ($path === '') {
            // no path - try home
            if (class_exists('HomeController')) {
                $c = new HomeController($this->config);
                echo $c->index();
                return;
            }
            http_response_code(404);
            echo "HomeController not found.";
            return;
        }

        $parts = explode('/', $path);
        $controllerName = ucfirst(array_shift($parts)) . 'Controller';
        $action = array_shift($parts) ?? 'index';
        $params = $parts;

        if (!class_exists($controllerName)) {
            http_response_code(404);
            echo "Controller {$controllerName} not found.";
            return;
        }

        $controller = new $controllerName($this->config);
        if (!method_exists($controller, $action)) {
            http_response_code(404);
            echo "Action {$action} not found on controller {$controllerName}.";
            return;
        }

        // Call with params (if any)
        echo call_user_func_array([$controller, $action], $params);
    }
}