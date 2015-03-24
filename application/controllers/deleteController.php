<?php


	$HG =& getInstance ();
	
	
	$deletehandler = new Handler ();

	$deletehandler -> rules (array ('/^delete$/', '/^.+$/', '/^[0-9]+$/'))
			//-> id ('index')
			-> handler (function () {
					$HG = getInstance (); 
					$pathContext = $HG -> getPathContext ();
					$boardName = $pathContext [1];
					$articleId = $pathContext [2];

					$boardMapper = $HG -> loader -> mapper ('BoardMapper');
					$bool = $boardMapper -> removeArticle ($boardName, $articleId);
					$HG -> setContentType ('application/json');
					echo json_encode (array (result => $bool), JSON_FORCE_OBJECT);
			});

	$HG -> get ($deletehandler -> build ());

?>