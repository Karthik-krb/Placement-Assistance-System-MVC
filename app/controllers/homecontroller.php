<?php
// app/controllers/HomeController.php
class HomeController {
    private array $config;
    
    public function __construct(array $config = []) {
        $this->config = $config;
    }
    
    public function index() {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Include the home view
        require __DIR__ . '/../views/home.php';
    }
}
?>