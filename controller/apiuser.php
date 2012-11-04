<?php

class apiuser extends controller {

	function __construct() {
		parent::__construct();
		if ($this->apiaccess->access() < 1) {
			$this->json->error('Action does not exist');
			die();
		}
	}

	function index() {

		$this->json->error('Method not specified');
	}

	function add() {
		$ip = $_GET['ip'];

		if (empty($ip)) {
			$this->json->error('Missing arguments');
			die();
		}

		if ($this->apiaccess->validIp($ip)) {
			$this->json->error('Ip already in use');
			die();
		}
		$ip = mysql_real_escape_string($ip);
		$access = '0';
		$apikey = $this->apiaccess->genKey($ip);

		mysql_query("INSERT INTO apiusers (ipaddr, access, apikey) VALUES ('$ip', '$access', '$apikey')");
		$this->json->success('User successfully added with key ' . $this->apiaccess->genKey($ip));

	}

	function del() {
		$ip = $_GET['ip'];

		if (empty($ip)) {
			$this->json->error('Missing arguments');
			die();
		}

		if (!$this->apiaccess->validIp($ip)) {
			$this->json->error('No such apiuser');
			die();
		}

		$ip = mysql_real_escape_string($ip);
		mysql_query("DELETE FROM apiusers WHERE ipaddr='$ip'");
		$this->json->success('User successfully deleted');
	}
}
?>
