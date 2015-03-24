<?php

	$editHandler = new Handler ();
	$editHandler 	-> rules (array ('/^edit$/', '/^.+$/', '/^[0-9]+$/'))
					-> handler (function () {
						$HG = getInstance ();
						$pathContext = $HG -> getPathContext ();
						$boardName = $pathContext [1];
						$articleId = $pathContext [2];
						$boardMapper = $HG -> loader -> mapper ('BoardMapper');
						$bool = $boardMapper -> editArticle ($boardName, $articleId, $_POST);
						$HG -> setContentType ('application/json');
						header ('Location: http://localhost/application/public/index.html#/dashboard');
						echo json_encode (array ("result" => $bool), JSON_FORCE_OBJECT);
			});

	$HG = getInstance ();
	$HG -> post ($editHandler -> build ());

?>