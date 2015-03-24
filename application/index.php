<?php
	/* this index.php file will be loaded by the framework.
	 * so you can use this file as a bootstrapper which load
	 * what your application need to use like controllers, database, mapper etc.
	 */



	$HG =& getInstance ();

	$HG -> domain = 'http://localhost';

	$dbConfig = new DBConfig ();
	$dbConfig 	-> username ('admin')
				-> password ('2642805')
				-> hostname ('localhost')
				-> database ('blog')
				-> driver ('MysqliDriver');
	$config = $dbConfig -> build ();

	$HG -> loader -> database ('MysqliDriver', $config);
	
	$HG -> loader //-> controller ('mainController')
				-> controller ('jsonController')
				-> controller ('boardsController')
				-> controller ('uploadController')
				-> controller ('insertController')
				-> controller ('articleController')
				-> controller ('deleteController')
				-> controller ('editController')
				-> controller ('cookieController')
				-> controller ('fbLoginController')
				-> controller ('commentsController')
				-> controller ('authController')
				-> controller ('newtableController');
?>

