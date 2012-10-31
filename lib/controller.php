<?php

class controller {
    
    function __construct() {
        $defaultClasses = array(
            'json',
            'config',
        );
        
        foreach ($defaultClasses as $class) {
            $this->loadClass($class);
        }
        
        if ($this->config->autoload) {
            foreach ($this->config->autoload as $class) {
                $this->loadClass($class, 'classes');
            }
        }
    }
    
    function loadClass($class, $dir='lib') {
        if (file_exists("./$dir/" . $class . ".php")) {
            require_once("./$dir/" . $class . ".php");
            if (class_exists($class)) {
                $this->$class = new $class();
            } else {
                $this->json->error("Error in $class.php, cannot find class $class .");
            }
        } else {
            $this->json->error("File missing ($class.php).");
        }
    }
}

?>
