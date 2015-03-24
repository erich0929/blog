<?php

	$boardsHandler = new Handler ();
	$boardsHandler 	-> rules (array ('/^boards$/'))
					-> handler (function () {
						$HG = getInstance ();

						$boardMapper = $HG -> loader -> mapper ('BoardMapper');
						$boards = $boardMapper -> getBoards ();
						$HG -> setContentType ('application/json');
						$boardSet = array_values ($boards);
						echo json_encode ($boardSet);
			});

	$someBoardHandler = new Handler ();
	$someBoardHandler -> rules (array ('/^boards$/', '/^.+$/'))
					-> handler (function () {

						$HG = getInstance ();
						$id = $_GET ['id'];
						$limit = $_GET ['limit'];
						$pathContext = $HG -> getPathContext ();
						$boardName = $pathContext [1];
						$boardMapper = $HG -> loader -> mapper ('BoardMapper');
						// debug level
						// $HG -> loader -> showContext ();
						if ($boardName == 'All') {
							$articles = $boardMapper -> getAllArticles ($id, $limit);
						} else {
							$articles = $boardMapper -> getArticlesByBoard ($boardName, $id, $limit);
						}
						$HG -> setContentType ('application/json');
						echo json_encode (array_values ($articles));

					});		
	$findOneArticleController = new Handler ();
	$findOneArticleController -> rules (array ('/^boards$/', '/^.+$/', '/^[0-9]+$/'))
							-> handler (function () {
								$HG = getInstance ();
								$pathContext = $HG -> getPathContext ();
								$boardName = $pathContext [1];
								$articleId = $pathContext [2];
								$boardMapper = $HG -> loader -> mapper ('BoardMapper');
								$article = $boardMapper -> findOneArticle ($boardName, $articleId);
								$HG -> setContentType ('application/json');
								echo json_encode ($article, JSON_FORCE_OBJECT);


							});		
	$HG =& getInstance ();
	$HG -> get ($boardsHandler -> build ());
	$HG -> get ($someBoardHandler -> build ());
	$HG -> get ($findOneArticleController -> build ());

?>