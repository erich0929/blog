<?php

	$articleHandler = new Handler ();
	$articleHandler -> rules (array ('/^insert$/'))
					-> handler (function () {
						
						$HG = getInstance ();
						$articleMapper = $HG -> loader -> mapper ('ArticleMapper');
			
						if ($articleMapper -> insertArticle ($_POST)) {
							echo 'post';
							header ('Location: ' . 'http://localhost/application/public/index.html');
						}
						exit ('500');
					});

	$HG =& getInstance ();
	$HG -> post ($articleHandler -> build ());

?>