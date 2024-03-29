<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

const BASE_PATH = __DIR__;

require_once BASE_PATH . '/bootstrap.php';

session_start();

$routes = include 'router.php';

$requestUri = $_SERVER['REQUEST_URI'];

$queryPosition = strpos($requestUri, '?');

// Extract the path and query parameters
$cleanRoute = $queryPosition !== false ? substr($requestUri, 0, $queryPosition) : $requestUri;

// Original route with the query string
$route = $requestUri;

// Map out the controllers for the project
if (array_key_exists($cleanRoute, $routes)) {
    $action = $routes[$cleanRoute];

    [$controller, $method] = explode('::', $action);

    // Adjust the namespace and path based on the folder structure
    $controllerNamespace = 'Controller\\';
    $controllerPath = BASE_PATH . '/controllers/';
    
    // Get the subdirectory if it exists
    $subDir = str_replace(BASE_PATH, '', dirname(__FILE__));
    $subDirNamespace = str_replace('/', '\\', ltrim($subDir, '/'));
    
    // Append the subdirectory namespace
    $controllerNamespace .= $subDirNamespace;
    
    // Require the controller file
    require $controllerPath . $controller . '.php';

    // Assuming the controllers are in a namespace
    $controller = $controllerNamespace . $controller;

    // Check if the method requires parameters
    $reflectionMethod = new ReflectionMethod($controller, $method);
    $parameters = $reflectionMethod->getParameters();

    $queryParams = [];

    // Extract parameters from query string if they are required
    if ($queryPosition !== false) {
        parse_str(substr($requestUri, $queryPosition + 1), $queryParams);
    }

    // Pass $queryParams to the method
    $controllerInstance = new $controller($dispatcher);

    // Check if the method requires parameters
    $reflectionMethod = new ReflectionMethod($controller, $method);
    $parameters = $reflectionMethod->getParameters();

    // If the method has parameters, pass them
    $methodArguments = [];
    foreach ($parameters as $parameter) {
        $parameterName = $parameter->getName();
        $methodArguments[] = $queryParams[$parameterName] ?? null;
    }

    // Call the method with the arguments
    $controllerInstance->$method(...$methodArguments);
} else {

    $logger->error("Page not found, Check the config for additional path");
    abort();
}
