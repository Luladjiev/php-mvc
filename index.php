<?php

ob_start();
include('config/Autoloader.class.php');

try {
    $controllerName = 'Index';
    if (isset($_GET['controller']) && !empty($_GET['controller'])) {
        $controllerName = $_GET['controller'];
        $controllerName = strtolower($controllerName);
        $controllerName = ucfirst($controllerName);
    }
    
    $className = "MVC\\Controllers\\{$controllerName}Controller";
    
    $class = new $className();
    
    if ($class instanceof MVC\Classes\Controller === false) {
        throw new Exception("$className is not an instance of Controller");
    }
    
    $method = 'index';
    if (isset($_GET['method']) && !empty($_GET['method'])) {
        $method = strtolower($_GET['method']);
    }
    
    if (!method_exists($class, $method)) {
        throw new Exception("Method $method not found in class $className");
    }
    
    $class->setParams($_GET, $_POST);
    $class->$method();
} catch(Exception $e) {
    var_dump($e);
}

$content = ob_get_contents();
ob_end_clean();

echo $content;
