<?php

	$newtableHandler = new Handler ();
	$newtableHandler -> rules (array ('/^newtable$/'))
	-> handler (function () {
		$HG = getInstance (); 
		$boardName = $_GET ['boardName'];
		$description = $_GET ['description'];
		echo $boardName;
		$boardMapper = $HG -> loader -> mapper ('BoardMapper');
		$HG -> setContentType ('application/json');
		$result = $boardMapper -> createBoard ($boardName, $description);
		echo json_encode (array ('result' => $result));
	});
	$HG =& getInstance ();
	$HG -> get ($newtableHandler -> build ());

?>