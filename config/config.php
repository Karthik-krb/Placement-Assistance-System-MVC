<?php
// config/config.php
return [
    'basePath' => '/PAS/public',  // Base path to strip from URLs
    
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'placement_data',
        'user' => 'root',
        'pass' => 'karthikrb@27',
    ],
    
    'app' => [
        'name' => 'Placement Assistance System',
        'env' => 'development', // development or production
    ],
];
