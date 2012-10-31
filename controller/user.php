<?php

class user extends controller {

	function index() {
		$this->json->error('Method not specified');
	}

	function add() {
		$user = $_POST['ident'];
		$pass = $_POST['password'];

		if (empty($user) || empty($pass)) {
			$this->json->error('Missing arguments');
			die();
		}

		if (!$this->bnc->validUser($user)) {
			$this->json->error('Ident already in use');
			die();
		}

		$this->sbnc->Call('adduser', array($user, $pass));
		$this->json->success('User successfully added');

	}
}
?>
