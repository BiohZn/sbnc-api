<?php

/*******************************************************************************
 * shroudBNC - an object-oriented framework for IRC                            *
 * Copyright (C) 2005-2011 Gunnar Beutner                                      *
 *                                                                             *
 * This program is free software; you can redistribute it and/or               *
 * modify it under the terms of the GNU General Public License                 *
 * as published by the Free Software Foundation; either version 2              *
 * of the License, or (at your option) any later version.                      *
 *                                                                             *
 * This program is distributed in the hope that it will be useful,             *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of              *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               *
 * GNU General Public License for more details.                                *
 *                                                                             *
 * You should have received a copy of the GNU General Public License           *
 * along with this program; if not, write to the Free Software                 *
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. *
 *******************************************************************************/

include_once('itype.php');
include_once('./lib/config.php');
include_once('./lib/json.php');

class sbnc {
	var $socket, $num, $user, $pass, $error=False;
	var $jsn;

	function __construct() {
		$cfg = new config();
		$this->jsn = new json();

		$this->num = 0;
		$this->user = $cfg->sbnc_user;
		$this->pass = $cfg->sbnc_pass;
		$this->socket = @fsockopen($cfg->sbnc_host, $cfg->sbnc_port);

		if ($this->socket == FALSE) {
			$this->jsn->error('Socket could not be connected.');
			$this->error = True;
		}

		if (!@fputs($this->socket, "RPC_IFACE\n") && !$this->error) {
			$this->jsn->error('Cannot connect to sBNC Server');
			$this->error = True;
		}

		while (($line = @fgets($this->socket)) != FALSE) {
			if (strstr($line, "RPC_IFACE_OK") != FALSE) {
				break;
			}

			if (strstr($line, "[RPC_BLOCK]") != FALSE) {
				$this->jsn->error('Runtime error occured in the RPC system: This IP address is blocked.');
				$this->error = True;
			}
		}
	}

	function Destroy() {
		fclose($this->socket);
	}


	function CallAs($user, $command, $parameters = array()) {
		if (!$this->error) {
		if ($user == FALSE) {
			$cmd = array($this->user, $this->pass);
		} else {
			$cmd = array($user, $this->user . ':' . $this->pass);
		}

		array_push($cmd, $command);
		array_push($cmd, $parameters);
	
		fputs($this->socket, itype_fromphp($cmd) . "\n");

		$line = fgets($this->socket, 128000);

		if ($line === false) {
			die('Transport layer error occured in the RPC system: fgets() failed.');
		}


		$line = str_replace(array("\r", "\n"), array("", ""), $line);

		$parsedResponse = itype_parse($line);

		if ($parsedResponse[0] == 'empty') {
			die('IType parsing error occured in RPC system when parsing the string "' . $line . '"');
		}

		$response = itype_flat($parsedResponse);

		if (IsError($response)) {
			$code = GetCode($response);

			if ($code != 'RPC_ERROR') {
				die('Runtime error occured in the RPC system: [' . $code . '] ' . GetResult($response));
			}
		}

		return $response;
		} else { $this->jsn->error('Error in sBNC connection'); }
	}

	function Call($command, $parameters = array()) {
		return $this->CallAs(FALSE, $command, $parameters);
	}

	function MultiCallAs($user, $commands) {
		$results = array();

		while (count($commands) > 0) {
			$spliced_commands = array_splice($commands, 0, 10);

			$spliced_results = $this->CallAs($user, 'multicall', array( $spliced_commands ));

			$results = array_merge($results, $spliced_results);
		}

		return $results;
	}

	function MultiCall($commands) {
		return $this->MultiCallAs(FALSE, $commands);
	}

	function IsValid() {
	    return ($this->socket != FALSE);
	}

	function Relogin($user, $pass) {
	    $this->user = $user;
	    $this->pass = $pass;
	}
}

function IsError($result) {
	if (is_a($result, 'itype_exception')) {
		return true;
	} else {
		return false;
	}
}

function GetCode($result) {
	if (IsError($result)) {
		return $result->GetCode();
	} else {
		return '';
	}
}

function GetResult($result) {
	if (IsError($result)) {
		return $result->GetMessage();
	} else {
		return $result;
	}
}

?>
