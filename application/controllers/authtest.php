<?php

	$HG =& getInstance ();
	$testHandler = new Handler ();
	$testHandler -> rules (array ('/^authtest$/'))
	-> prehook ($HG -> adminSecureHandler)

	-> handler (function () {
		$HG = getInstance (); 
		echo "It's security page.";
			
	});
	$HG =& getInstance ();
	$HG -> get ($testHandler -> build ());


?>