<?php

	$cookieHandler = new Handler ();
	$cookieHandler -> rules (array ('/^cookie$/'))
					-> handler (function () {
				$HG = getInstance (); 
				setcookie('name', 'erich0929', time () + 5, '/');
				header ('Location:' . '/application/public/index.html#/write');
				return;
			});
	$HG =& getInstance ();
	$HG -> get ($cookieHandler -> build ());


?>