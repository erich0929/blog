<?php

	$jsonHandler = new Handler ();
	$jsonHandler 	-> rules (array ('/^json$/'))
					-> handler (function () {
						$HG =& getInstance ();
						if ($HG -> getAccept () == 'application/json') {
							$HG -> setContentType ($HG -> getAccept ());
							echo json_encode (array ('data' => 'Hello world!'));
						} else {
							$HG -> setContentType ($HG -> getAccept ());
							echo json_encode ($HG -> pathContext);
						}
					});
	$HG =& getInstance ();
	$HG -> get ($jsonHandler -> build ());

?>