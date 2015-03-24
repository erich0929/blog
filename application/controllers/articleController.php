<?php

	$articleHandler = new Handler ();
	$articleHandler -> rules (array ('/^article$/', '/^[0-9]+$/'))
					-> handler (function () {
				$HG = getInstance (); 
	
				//$HG -> loader -> showContext ();
				
				$pathContext = $HG -> getPathContext ();
				$articleId = $pathContext [1];
				$articleMapper = $HG -> loader -> mapper ('ArticleMapper');
				$article = $articleMapper -> findOneArticle ($articleId);
				$HG -> setContentType ('application/json');
				if (!$article) {
					echo json_encode('No result');
					return;
				}
											
				echo json_encode ($article, JSON_FORCE_OBJECT);
				return;
				//print_r ($HG -> loader -> database ('MysqliDriver'));
			});
	$HG =& getInstance ();
	$HG -> get ($articleHandler -> build ());

?>