<?php
//print_r(phpinfo());
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

const BASE_PATH = __DIR__;

// require BASE_PATH . '/vendor/autoload.php'; // moved to boodstrap file
// require BASE_PATH . '/helper/functions.php';
require_once  BASE_PATH . '/bootstrap.php';

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
    abort();
}
