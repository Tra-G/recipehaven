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
    r('recipe/{id}/comment') => router('homeController', 'commentRecipe'),
    r('recipe/{id}/delete') => router('userController', 'deleteRecipe'),
    r('recipe/{id}/edit') => router('userController', 'editRecipe', 'recipe/edit'),
    r('recipe/new') => router('userController', 'addRecipe', 'recipe/new'),

    // Authentication
    r('login') => router('authController', 'login', 'auth/login'),
    r('register') => router('authController', 'register', 'auth/register'),
    r('logout') => router('authController', 'logout'),
    r('reset') => router('authController', 'forgotPassword'),
    r('reset/{string}') => router('authController', 'changePassword', 'auth/reset/change'),
    r('reset/{string}/change') => router('authController', 'changePasswordApi'),

    // User
    r('user/profile') => router('userController', 'profile', 'user/profile'),
    r('user/edit') => router('userController', 'editProfile'),
    r('user/password') => router('userController', 'changePassword'),

    // Admin Dashboard
    r('admin/dashboard') => router('adminController', 'index', 'admin/index'),

    // Admin User Management
    r('admin/users') => router('adminController', 'allUsers', 'admin/users/all'),
    r('admin/user/{id}/edit') => router('adminController', 'editUser', 'admin/users/edit'),
    r('admin/user/{id}/delete') => router('adminController', 'deleteUser'),

    // Admin Recipe Management
    r('admin/recipes') => router('adminController', 'allRecipes', 'admin/recipes/all'),
    r('admin/recipe/new') => router('adminController', 'addRecipe', 'admin/recipes/new'),
    r('admin/recipe/{id}/edit') => router('adminController', 'editRecipe', 'admin/recipes/edit'),
    r('admin/recipe/{id}/approve') => router('adminController', 'approveRecipe'),
    r('admin/recipe/{id}/delete') => router('adminController', 'deleteRecipe'),

    // Admin Profile Management
    r('admin/profile') => router('adminController', 'editProfile', 'admin/profile'),
    r('admin/password') => router('adminController', 'changePassword', 'admin/password'),

    // Admin Blog Management
    r('admin/blog') => router('adminController', 'allPosts', 'admin/blog/all'),
    r('admin/post/new') => router('adminController', 'addPost', 'admin/blog/new'),
    r('admin/post/{id}/edit') => router('adminController', 'editPost', 'admin/blog/edit'),
    r('admin/post/{id}/delete') => router('adminController', 'deletePost'),

    // Admin Category Management
    r('admin/categories') => router('adminController', 'allCategories', 'admin/categories/all'),
    r('admin/category/new') => router('adminController', 'addCategory', 'admin/categories/new'),
    r('admin/category/{id}/edit') => router('adminController', 'editCategory', 'admin/categories/edit'),
    r('admin/category/{id}/delete') => router('adminController', 'deleteCategory'),
);

?>