<?php

class sbnc {
    
    public $user, $pass, $host, $port;
    private $_socket;
    
    public function __construct() {
        $this->config = new config();
        
        $this->user = $this->config->sbnc_user;
        $this->pass = $this->config->sbnc_pass;
        $this->host = $this->config->sbnc_host;
        $this->port = $this->config->sbnc_port;
    }
    
    public function connect() {
        $this->_socket = socket_create(AF_INET, SOCK_STREAM, 0);
        
        $this->host = gethostbyname($this->host);
        
        if(!socket_connect($this->_socket, $this->host, $this->port)) {
            return;
        }

        $send = "USER $this->user . . :$this->user\r\nNICK $this->user\r\nPASS $this->pass\r\n"; 
        socket_write($this->_socket, $send, strlen($send));

        $data = socket_read($this->_socket, 4096);
        $i = 0;
        while (strstr($data, " 001 ") == NULL) {
            if (strstr($data, "Unknown user or wrong password.")) return;
            if ($this->_socket == 0) break;
            $data = socket_read($this->_socket, 4096);
            $i++;
            
            if ($i >= 5) break;
        }

        return $this;
    }

    private function write($send) {
        socket_write ($this->_socket, $send, strlen($send)); 
    }

    public function adduser($username, $password) { 
        $this->tobnc("adduser $username $password"); 
        return $this; 
    }
    
    public function deluser($username) { 
        $this->tobnc("deluser $username"); 
        return $this; 
    }
    
    public function tobnc($text) { 
        $this->write("PRIVMSG -sBNC :$text\r\n");
        return $this; 
    }
    
    public function say($to, $text) {
        $this->write("PRIVMSG $to :$text\r\n"); 
        return $this; 
    }
    
    public function simul($ident, $command) { 
        $this->write("simul $ident :$command\r\n");
        return $this; 
    }

    public function disconnect() {
        socket_shutdown($this->_socket, 0); 
        socket_close($this->_socket); 
    }
    
    public function __destruct() {
        if ($this->_socket) {
            $this->disconnect();
        }
    }
}

?>