<?php

namespace Router;

require_once '../../autoload.php';

class Router
{
    public static function route()
    {
        try {
            self::validateRequest();
            $controllerName = ucfirst($_GET['controller']);
            $actionName = $_GET['action'];

            self::loadController($controllerName);
            self::callAction($controllerName, $actionName);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    private static function validateRequest()
    {
        if (!isset($_GET['controller']) || !isset($_GET['action'])) {
            throw new \Exception("Controller and/or action not specified");
        }

        if (empty($_GET['controller']) || empty($_GET['action'])) {
            throw new \Exception("Controller and/or action not specified");
        }
    }

    private static function loadController($controllerName)
    {
        $controllerFilePath = realpath(__DIR__ . '/../Controller/') . '/' . $controllerName . '.php';

        if (!file_exists($controllerFilePath)) {
            throw new \Exception("Controller file not found");
        }

        require_once $controllerFilePath;

        $controllerClass = sprintf('Controller\%s', $controllerName);

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller class not found");
        }
    }

    private static function callAction($controllerName, $actionName)
    {
        $controllerClass = sprintf('Controller\%s', $controllerName);

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $actionName)) {
            throw new \Exception("Action not found");
        }

        $controllerInstance->$actionName();
    }
}

Router::route();
