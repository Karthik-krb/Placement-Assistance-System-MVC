<?php
// public/index.php
declare(strict_types=1);

// Show errors during development (turn off in production)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Load config (only once)
$configPath = __DIR__ . '/../config/config.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo "Configuration file not found: config/config.php";
    exit;
}
$config = require $configPath;

// Simple autoloader for Controllers, Models, Core, Config (case-insensitive fallback)
spl_autoload_register(function (string $class) {
    $base = __DIR__ . '/../app/';

    $candidates = [
        $base . 'Controllers/' . $class . '.php',
        $base . 'controllers/' . $class . '.php',
        $base . 'Models/'      . $class . '.php',
        $base . 'models/'      . $class . '.php',
        $base . 'Core/'        . $class . '.php',
        $base . 'core/'        . $class . '.php',
        $base . 'config/'      . $class . '.php',
        $base . 'Config/'      . $class . '.php',
    ];

    foreach ($candidates as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Create and run the App dispatcher (app/core/App.php expected)
$appFile1 = __DIR__ . '/../app/core/App.php';
$appFile2 = __DIR__ . '/../app/Core/App.php';
if (file_exists($appFile1) || file_exists($appFile2)) {
    // prefer lowercase core first if present
    $appFile = file_exists($appFile1) ? $appFile1 : $appFile2;
    require_once $appFile;

    if (!class_exists('App')) {
        http_response_code(500);
        echo "App class not found in {$appFile}";
        exit;
    }

    // Instantiate App; pass config if constructor accepts it
    try {
        $ref = new ReflectionClass('App');
        $app = $ref->getConstructor() ? $ref->newInstance($config) : $ref->newInstance();
        if (method_exists($app, 'run')) {
            $app->run();
            exit;
        } else {
            http_response_code(500);
            echo "App class found but run() method missing.";
            exit;
        }
    } catch (Throwable $e) {
        http_response_code(500);
        echo "App bootstrap error: " . htmlspecialchars($e->getMessage());
        exit;
    }
}

// If we reach here, App file missing — fallback to minimal manual routing
http_response_code(500);
echo "App dispatcher not available. Please add app/core/App.php or app/Core/App.php";
exit;