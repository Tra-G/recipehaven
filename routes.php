<?php

// Helper function to create route array
function router($controller, $action, $view=null) {
    return array(
        'controller' => $controller,
        'action' => $action,
        'view' => $view
    );
}

// Helper function to convert route to regex
function r($route) {
    // handle placeholder for {id}
    $route = str_replace("{id}", '(\d+)', $route);
    return '|^'. $route .'/?$|';
}

// Routes
$routes = array(
    // Front pages
    r('') => router('homeController', 'index', 'index'),
    r('about') => router('homeController', 'about', 'about'),
    r('contact') => router('homeController', 'contact', 'contact'),
    r('blog') => router('homeController', 'blog', 'blog/index'),
    r('blog/{id}') => router('homeController', 'post', 'blog/single'),

    // Authentication
    r('login') => router('authController', 'login', 'auth/login'),
    r('register') => router('authController', 'register', 'auth/register'),
    r('logout') => router('authController', 'logout'),
);

?>