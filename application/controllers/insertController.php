<?php

	$HG =& getInstance ();
	$articleHandler = new Handler ();
	$articleHandler -> rules (array ('/^insert$/'))
					-> preHook ($HG -> adminSecureHandler)
					-> handler (function () {
						
						$HG = getInstance ();
						$articleMapper = $HG -> loader -> mapper ('ArticleMapper');
			
						if ($articleMapper -> insertArticle ($_POST)) {
							echo 'post';
							header ('Location: ' . 'http://localhost/application/public/index.html');
						}
						exit ('500');
					});

	$HG -> post ($articleHandler -> build ());

?>