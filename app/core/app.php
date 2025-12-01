<?php

declare(strict_types=1);


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

        if ($this->basePath !== '' && str_starts_with($uri, $this->basePath)) {
            $uri = substr($uri, strlen($this->basePath));
            if ($uri === '') $uri = '/';
        }

        $path = rtrim($uri, '/');
        if ($path === '') $path = '/';

       
        $routes = [
            
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
            ['GET',  '/auth/company/register', 'CompanyAuthController@showRegister'],
            ['POST', '/auth/company/register', 'CompanyAuthController@register'],

            // Admin Dashboard routes
            ['GET',  '/admin',                   'AdminDashboardController@index'], // Redirect /admin to dashboard
            ['GET',  '/admin/dashboard',         'AdminDashboardController@index'],
            ['GET',  '/admin/candidates',        'AdminDashboardController@showCandidates'],
            ['GET',  '/admin/applications',      'AdminDashboardController@showApplications'],
            ['GET',  '/admin/companies',         'AdminDashboardController@showCompanies'],
            ['GET',  '/admin/shortlisted',       'AdminDashboardController@showShortlisted'],
            ['GET',  '/admin/feedback',          'AdminDashboardController@feedback'],
            ['POST', '/admin/feedback',          'AdminDashboardController@feedback'],
            ['GET',  '/admin/logout',            'AdminDashboardController@logout'],

            
            ['GET',  '/candidate',               'CandidateDashboardController@index'], 
            ['GET',  '/candidate/dashboard',     'CandidateDashboardController@index'],
            ['GET',  '/candidate/job-postings',  'CandidateDashboardController@jobPostings'],
            ['POST', '/candidate/job-postings',  'CandidateDashboardController@jobPostings'],
            ['GET',  '/candidate/applications',  'CandidateDashboardController@checkApplications'],
            ['GET',  '/candidate/edit-profile',  'CandidateDashboardController@showEditProfile'],
            ['POST', '/candidate/edit-profile',  'CandidateDashboardController@updateProfile'],
            ['GET',  '/candidate/feedback',      'CandidateDashboardController@feedback'],
            ['POST', '/candidate/feedback',      'CandidateDashboardController@feedback'],
            ['GET',  '/candidate/logout',        'CandidateDashboardController@logout'],

            
            ['GET',  '/company',                 'CompanyDashboardController@index'], 
            ['GET',  '/company/login',           'CompanyAuthController@showLogin'], 
            ['GET',  '/company/dashboard',       'CompanyDashboardController@index'],
            ['GET',  '/company/jobposting',      'CompanyDashboardController@showJobPostingForm'],
            ['POST', '/company/jobposting',      'CompanyDashboardController@createJobPosting'],
            ['GET',  '/company/postedjobs',      'CompanyDashboardController@showPostedJobs'],
            ['GET',  '/company/application',     'CompanyDashboardController@showApplications'],
            ['GET',  '/company/applications',    'CompanyDashboardController@showApplications'],
            ['POST', '/company/applications',    'CompanyDashboardController@showApplications'],
            ['GET',  '/company/shortlisted',     'CompanyDashboardController@showShortlisted'],
            ['GET',  '/company/email',           'CompanyDashboardController@sendEmail'],
            ['POST', '/company/email',           'CompanyDashboardController@sendEmail'],
            ['GET',  '/company/feedback',        'CompanyDashboardController@feedback'],
            ['POST', '/company/feedback',        'CompanyDashboardController@feedback'],
            ['GET',  '/company/logout',          'CompanyDashboardController@logout'],
        ];

      
        foreach ($routes as $r) {
            [$allowedMethod, $routePath, $handler] = $r;
            if ($method !== $allowedMethod) continue;
            if ($routePath === rtrim($path, '/')) {
                $this->invokeHandler($handler);
                return;
            }
        }

      
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
        
        $controller->{$action}();
    }

    private function dispatchByConvention(string $path, string $method): void
    {
       
        $path = ltrim($path, '/');
        if ($path === '') {
            
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

      
        echo call_user_func_array([$controller, $action], $params);
    }
}