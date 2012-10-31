<?php

class main extends controller {
    function index() {
        if ($this->sbnc->connect()) {
            $this->json->success('Working as it should');
        }
    }
}

?>
