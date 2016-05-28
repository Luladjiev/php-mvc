<?php
namespace MVC;

class Autoloader 
{
    protected static $paths = ['../classes', '../controllers', '../templates'];
    
    static public function Load($class) 
    {
        $prefix = __NAMESPACE__."\\";
        
        $lenght = strlen($prefix);
        
        if (strncmp($prefix, $class, $lenght) !== 0) {
            return;
        }
        
        $relative_class = substr($class, $lenght);
        
        foreach(self::$paths as $path) {
            $file = __DIR__."/$path/$relative_class.php";
            
            if (file_exists($file)) {
                require $file;
                break;
            }
        }
    }
}    

spl_autoload_register(__NAMESPACE__.'\Autoloader::Load');
