<?php

class apiaccess {

	var $config, $json;
	var $details;
	var $apikey, $ip, $bnclimit;

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

		if (empty($_GET['apikey'])) {
			$this->json->error('No API key supplied');
			die();
		}

		$apikey = mysql_real_escape_string($_GET['apikey']);
		$remote = $_SERVER['REMOTE_ADDR'];

		$this->apikey = $apikey;

		$sql = "SELECT id, ipaddr, access, bncs, bnclimit FROM apiusers WHERE apikey = '$apikey' LIMIT 1";
		$details = mysql_query($sql);
		if (mysql_num_rows($details) != 1) {
			$this->json->error('Faulty apikey');
			die();
		}

		$details = mysql_fetch_array($details);

		$this->bnclimit = $details['bnclimit'];
		$this->bncs = $details['bncs'];
		$this->ip = $details['ipaddr'];

		if ($this->ip != $remote) {
			$this->json->error('API Call from unkown host');
			die();
		}

		$this->details = $details;
	}

	function access() {
		if (!empty($this->apikey)) {
			$apikey = $this->apikey;
			$access = mysql_query("SELECT access FROM apiusers WHERE apikey='$apikey'");
			if (mysql_num_rows($access) > 0) {
				$access = mysql_fetch_array($access);
				return ($access['access'] > 0);
			} else {
				return False;
			}
		} else {
			return False;
		}
	}

	function genKey($ip) {
		return sha1($ip);
	}

	function validIp($ip) {
		$ip = mysql_real_escape_string($ip);
		$check = mysql_query("SELECT id FROM apiusers WHERE ipaddr='$ip' LIMIT 1");
		return (mysql_num_rows($check) > 0);
	}

	function validName($name) {
		$name = mysql_real_escape_string($name);
		$check = mysql_query("SELECT id FROM apiusers WHERE name='$name' LIMIT 1");
		return (mysql_num_rows($check) > 0);
	}
}
