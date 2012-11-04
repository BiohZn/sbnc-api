<?php

class user extends controller {

	function index() {
		$this->json->error('Method not specified');
	}

	function add() {
		$user = $_GET['ident'];
		$pass = $_GET['password'];

		if ($this->apiaccess->bncs == $this->apiaccess->bnclimit) {
			$this->json->error('Bouncer limit reached');
			die();
		}

		if (empty($user) || empty($pass)) {
			$this->json->error('Missing arguments');
			die();
		}

		if ($this->bnc->validUser($user)) {
			$this->json->error('Ident already in use');
			die();
		}

		$apikey = $this->apiaccess->apikey;
		mysql_query("UPDATE apiusers SET bncs=bncs+1 WHERE apikey='$apikey'");
		$this->sbnc->Call('adduser', array($user, $pass));
		$this->json->success('User successfully added');

	}

	function del() {
		$user = $_GET['ident'];

		if (empty($user)) {
			$this->json->error('Missing arguments');
			die();
		}

		if (!$this->bnc->validUser($user)) {
			$this->json->error('No such user');
			die();
		}

		$apikey = $this->apiaccess->apikey;
		mysql_query("UPDATE apiusers SET bncs=bncs-1 WHERE apikey='$apikey'");
		$this->sbnc->Call('deluser', array($user));
		$this->json->success('User successfully deleted');
	}
}
?>
