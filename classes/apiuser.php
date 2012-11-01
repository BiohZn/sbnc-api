<?php

class apiuser {

	var $config, $json;
	var $details;

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

		if (empty($_POST['apikey'])) {
			$this->json->error('No API key supplied');
			die();
		}

		$apikey = mysql_real_escape_string($_POST['apikey']);
		$remote = $_SERVER['REMOTE_ADDR'];

		$details = mysql_query("SELECT id, ipaddr, access FROM apiusers WHERE apikey = $apikey LIMIT 1");
		if (mysql_num_rows($details) != 1) {
			$this->json->error('Faulty apikey');
			die();
		}

		$details = mysql_row($details);

		if ($details['ipaddr'] != $remote) {
			$this->json->error('API Call from unkown host');
			die();
		}

		$this->details = $details;
	}
}
