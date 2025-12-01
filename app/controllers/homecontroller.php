<?php

class HomeController {
    private array $config;
    
    public function __construct(array $config = []) {
        $this->config = $config;
    }
    
    public function index() {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        
        require __DIR__ . '/../views/home.php';
    }
}
?>