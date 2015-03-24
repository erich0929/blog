<?php

	$authHandler = new Handler ();
	$authHandler -> rules (array ('/^auth$/'))
	
	-> handler (function () {
		
		$HG =& getInstance ();
		$authMapper = $HG -> loader -> mapper ('AuthMapper');
		$auth = $authMapper -> auth ();
		echo json_encode ($auth);
		return;
	});
	
	$HG =& getInstance ();
	$HG -> get ($authHandler -> build ());

?>