<?php
namespace MVC;

class Controller 
{
    private $params = [];
    private $system_params = ['controller', 'method'];
    
    final public function setParams($get, $post) 
    {
        foreach($this->system_params as $param) {
            if (array_key_exists($param, $get)) {
                unset($get[$param]);
            }
        }
        
        $this->params = array_merge($get, $post);
    }
    
    final protected function param($key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        
        return null;
    }
    
    final protected function loadTemplate($class, $method, $params = array())
    {
        $className = __NAMESPACE__.'\\'.$class."Template";
        if (!class_exists($className)) {
            throw new \Exception("Template $class does not exists");
        }
        
        $template = new $className();
        
        if ($template instanceof Template === false) {
            throw new \Exception("$class must be instance of Template");
        }
        
        if (!method_exists($template, $method)) {
            throw new \Exception("Method $method of class $class does not exists");
        }
        
        ob_start();
        
        $template->$method($params);
        
        $content = ob_get_contents();
        
        ob_clean();
        
        $template->html($content);
    }
}
