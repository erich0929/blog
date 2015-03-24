<?php
	/* this index.php file will be loaded by the framework.
	 * so you can use this file as a bootstrapper which load
	 * what your application need to use like controllers, database, mapper etc.
	 */



	$HG =& getInstance ();

	$HG -> domain = 'http://localhost';

	$HG -> adminSecureHandler = function () {
		$HG =& getInstance ();
		$authMapper = $HG -> loader -> mapper ('AuthMapper');
		$userinfo = $authMapper -> auth ();
		if ($userinfo ['userName'] != 'admin') {
			header ('Location: ' . $HG -> domain . '/application/public/index.html#/login');
			return false;
		}
		return true;
	};

	$HG -> loginedSecureHandler = function () {
		$HG =& getInstance ();
		$authMapper = $HG -> loader -> mapper ('AuthMapper');
		$userinfo = $authMapper -> auth ();
		if ($userinfo ['userName'] == 'guest') {
			header ('Location: ' . $HG -> domain . '/application/public/index.html#/login');
			return false;
		}
		return true;
	};

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
				-> controller ('editTableController');
				
?>

