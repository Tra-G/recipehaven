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
    // Handle parameters {id}, {string}, {page} and {rating}
    $route = str_replace("{id}", '([0-9]+)', $route);
    $route = str_replace("{string}", '([a-zA-Z0-9]+)', $route);
    $route = str_replace("{page}", '([0-9]+)', $route);
    $route = str_replace("{rating}", '([1-5])', $route);
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
    r('search') => router('homeController', 'search', 'search'),

    // Recipes
    r('recipes') => router('homeController', 'allRecipes', 'recipe/all'),
    r('recipe/{id}') => router('homeController', 'singleRecipe', 'recipe/single'),
    r('recipe/{id}/save') => router('homeController', 'saveRecipe'),
    r('recipe/{id}/unsave') => router('homeController', 'unsaveRecipe'),
    r('recipe/{id}/rate/{rating}') => router('homeController', 'rateRecipe'),

    // Authentication
    r('login') => router('authController', 'login', 'auth/login'),
    r('register') => router('authController', 'register', 'auth/register'),
    r('logout') => router('authController', 'logout'),
    r('reset') => router('authController', 'forgotPassword', 'auth/reset/index'),
    r('reset/{string}') => router('authController', 'changePassword', 'auth/reset/change'),
    r('reset/{string}/change') => router('authController', 'changePasswordApi', 'auth/reset/api'),
);

?>