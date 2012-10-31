<?php

class bnc {

	var $sbnc;

	function __construct() {
		$this->sbnc = new sbnc();
	}

	function validUser($user) {
		$users = strtolower($this->sbnc->Call('tcl', array('bncuserlist')));
		$users = explode(" ", $users);
		$user = strtolower($user);
		return !in_array($user, $users);
	}

}
