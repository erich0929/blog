<?php

	require_once (BASEPATH . 'core/Controller.php');
	require_once (BASEPATH . 'core/Builder.php');

	function &getInstance () {
		return HGController::getInstance ();
	}

	// load library
	// facebook sdk - It requires php5.4 upper version.
	require_once (BASEPATH . 'libs/facebook-php/autoload.php');
	require_once (APPPATH . 'index.php');

	// session start
	session_start ();
	$HG -> route ();

?>