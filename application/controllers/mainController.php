<?php

	$HG =& getInstance ();
	
	
	$handler = new Handler ();

	$handler -> rule ('/^boards$/')
			//-> id ('index')
			-> handler (function () {
				$HG = getInstance (); 
	
				//$HG -> loader -> showContext ();
				$accept = $HG -> getAccept ();
				if (true) {
					$boardMapper = $HG -> loader -> mapper ('BoardMapper');
					$boards = $boardMapper -> getBoards ();
					$HG -> setContentType ('application/json');
					echo json_encode ($boards);
				}

				//print_r ($HG -> loader -> database ('MysqliDriver'));
			});
		//	-> preHook (function () { echo 'prehook1'; return true;})
		//	-> preHook (function () { echo 'prehook2'; return true;})
		//	-> postHook (function () {echo 'posthook1'; return false;})
		//	-> postHook (function () {echo 'posthook2'; return true;});
	$HG -> get ($handler -> build ());
	//$HG -> showContext ();
	

?>