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
		$name = $_GET['name'];
		if (empty($ip) || empty($name)) {
			$this->json->error('Missing arguments');
			die();
		}

		if ($this->apiaccess->validIp($ip)) {
			$this->json->error('Ip already in use');
			die();
		}

		if ($this->apiaccess->validName($name)) {
			$this->json->error('Name already in use');
			die();
		}

		$namelen = explode(" ", $name);

		if (count($namelen) > 1) {
			$this->json->error('Name must not contain spaces');
			die();
		}

		$ip = mysql_real_escape_string($ip);
		$name = mysql_real_escape_string($name);
		$apikey = $this->apiaccess->genKey($ip);

		mysql_query("INSERT INTO apiusers (name, ipaddr, apikey) VALUES ('$name', '$ip', '$apikey')");
		$this->json->success('User successfully added with key ' . $this->apiaccess->genKey($ip));

	}

	function setlimit() {
		$ip = $_GET['ip'];
		$name = $_GET['name'];
		$limit = $_GET['limit'];

		if ((!empty($ip) || !empty($name)) && !empty($limit)) {

			if (!is_numeric($limit)) {
				$this->json->error('Limit has to be numeric');
				die();
			}

			if (!empty($ip)) {
				if ($this->apiaccess->validIp($ip)) {
					mysql_query("UPDATE apiusers SET bnclimit='$limit' WHERE ipaddr='$ip'");
					$this->json->success('Limit successfully updated');
				}
			} else {
				if ($this->apiaccess->validName($name)) {
					mysql_query("UPDATE apiusers SET bnclimit='$limit' WHERE name='$name'");
					$this->json->success('Limit successfully updated');
				}
			}
		} else {
			$this->json->error('Missing arguments');
            die();
		}
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
