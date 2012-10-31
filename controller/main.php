<?php

class main extends controller {
    function index() {
		if ($this->sbnc->isValid()) {
			$channels = $this->sbnc->Call("getchannels");

			print "<pre>";
			var_dump($channels);
			print "</pre>";
		}
    }
}

?>
