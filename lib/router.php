<?php

require_once 'lib/controller.php';
require_once 'lib/json.php';

class router{
    
    function __construct() {
        
        $this->json = new json();
        
        $params = $this->url_array($_SERVER['PATH_INFO']);
        if (!$params) {
            $reqClass = 'main';
            $reqFunc = 'index';
        } elseif (count($params)>1) {
            $reqClass = array_shift($params);
            $reqFunc = array_shift($params);
        } else {
            $reqClass = array_shift($params);
            $reqFunc = 'index';
        }
        $this->run($reqClass, $reqFunc, $params);
    }

    function run($reqClass, $reqFunc, $params) {
        if (file_exists("./controller/" . $reqClass . ".php")) {
            require_once("./controller/" . $reqClass . ".php");
            if (class_exists($reqClass)) {
                $controller = new $reqClass();
                if (method_exists($controller, $reqFunc)) {
                    if ($params) {
                        call_user_func_array(array($controller, $reqFunc), $params);
                    } else {
                        call_user_func(array($controller,$reqFunc));
                    }
                } else {
                    $this->json->error('Action does not exist');
                }
            } else {
                $this->json->error('Function not found');
            }
        } else {
            $this->json->error('Function not found');
        }
    }

    function trim_url($url){
        if (substr($url, strlen($url)-1) == '/') return substr($url, 1, strlen($url)-2);
        else return substr($url, 1, strlen($url)-1);
    }
    function url_array($url){
        $url = explode('/', $this->trim_url($url));
        if (count($url) == 1 && strlen($url[0]) < 1) {
            return false;
        } else {
            return $url;
        }
    }
}

?>
