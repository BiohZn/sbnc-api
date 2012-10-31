<?php

class apiuser {

	var $config, $json;

	function __construct() {

		$this->config = new config();
		$this->json = new json();

		if (!@mysql_connect($this->config->db_host,$this->config->db_user,$this->config->db_pass)) {
			$this->json->error('Unable to connect to database', 500);
			die();
		}

		if (!@mysql_select_db($this->config->db_data)) {
			$this->json->error('Unable to select database', 500);
			die();
		}
	}
}
