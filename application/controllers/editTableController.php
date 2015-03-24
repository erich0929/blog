<?php

	$HG =& getInstance ();
	$newtableHandler = new Handler ();
	$newtableHandler -> rules (array ('/^newtable$/'))
	-> preHook ($HG -> adminSecureHandler)
	-> handler (function () {
		$HG = getInstance (); 
		$boardName = $_POST ['boardName'];
		$description = $_POST ['description'];
		//echo $boardName;
		$boardMapper = $HG -> loader -> mapper ('BoardMapper');
		$HG -> setContentType ('application/json');
		$result = $boardMapper -> createBoard ($boardName, $description);
		echo json_encode (array ('result' => $result));
	});
	$HG -> post ($newtableHandler -> build ());

	$droptableHandler = new Handler ();
	$droptableHandler -> rules (array ('/^droptable$/'))
	-> preHook ($HG -> adminSecureHandler)
	-> handler (function () {
		$HG = getInstance (); 
		$boardName = $_POST ['boardName'];
		//echo $boardName;
		$boardMapper = $HG -> loader -> mapper ('BoardMapper');
		$HG -> setContentType ('application/json');
		$result = $boardMapper -> dropBoard ($boardName);
		echo json_encode (array ('result' => $result));
	});

	$HG -> post ($droptableHandler -> build ());

?>