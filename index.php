<?php

// start session
session_start();

// Load the helper functions
require_once(__DIR__.'/models/helper.php');

// Include the routes
require_once('routes.php');

// Get the requested URL
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Check if the requested URL is valid
$route = null;
foreach ($routes as $pattern => $routeConfig) {
    if (preg_match($pattern, $url, $matches)) {
        array_shift($matches); // Remove the full match from the matches array
        $route = $routeConfig;
        break;
    }
}

if ($route) {
    // If the URL is valid, redirect to the appropriate controller and action
    $controller = $route['controller'];
    $action = $route['action'];
    $view = $route['view'];

    require('controllers/' . $controller . '.php');
    $controller = new $controller();
    if (method_exists($controller, $action)) {
        // If the action exists in the controller, execute it and pass the arguments
        $data = $controller->$action(...$matches);

        // If the action returns data, extract it for use in the view
        if (isset($data)) {
            extract($data);
        }

        // Load the appropriate view for the action
        if ($view) {
            require('views/' . $view . '.php');
        }
    } else {
        // If the action does not exist in the controller, redirect to the 404 page
        http_response_code(404);
    }
} else {
    // If the URL is not valid, redirect to the 404 page
    http_response_code(404);
}

// close session
session_write_close();

?>