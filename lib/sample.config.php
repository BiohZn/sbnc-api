<?php

class config {
    
    var $sbnc_user, $sbnc_pass, $sbnc_host, $sbnc_port;
    var $db_host, $db_user, $db_pass, $db_data;
    var $autoload;
    
    function __construct() {
        /*
         * Must be admin on the Bouncer
         */
        $this->sbnc_user = '';
        $this->sbnc_pass = '';
        $this->sbnc_host = '';
        $this->sbnc_port = 9000;
        
        /*
         * MySQL Database details
         */
        $this->db_user = '';
        $this->db_pass = '';
        $this->db_host = '';
        $this->db_data = '';
        
        /*
         * Classes that should be loaded
         */
        $this->autoload = array(
            'sbnc',
        );
    }
    
}

?>
