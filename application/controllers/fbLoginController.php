<?php
	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\FacebookResponse;
	use Facebook\FacebookSDKException;
	use Facebook\FacebookRequestException;
	use Facebook\Helpers\FacebookJavaScriptLoginHelper;
	use Facebook\FacebookAuthorizationException;
	use Facebook\GraphObject;
	use Facebook\Entities\AccessToken;
	use Facebook\HttpClients\FacebookCurlHttpClient;
	use Facebook\HttpClients\FacebookHttpable;

	$facebookConfig = array ();
	$facebookConfig ['appId'] = '807361542684806';
	$facebookConfig ['appSecret'] = 'ceeb8cafd04c9da9c2bff0176ab3f58a';

	$facebookHandler = new Handler ();
	$facebookHandler -> rules (array ('/^facebookLogin$/'))
	-> handler (function () use ($facebookConfig){
		$HG = getInstance (); 		
		$facebookMapper = $HG -> loader -> mapper ('FacebookMapper');
			print_r ($facebookConfig);
			$facebookMapper -> makeSession ($facebookConfig ['appId'], $facebookConfig ['appSecret'], $_GET ['access']);
			$userinfo = $facebookMapper -> getUser ();
			print_r ($userinfo);
    		/* ---- Session Variables -----*/
    		$_SESSION['fbid'] = $userinfo ['fbid'];          
    		$_SESSION['userName'] = $userinfo ['userName'];
   	 		$_SESSION['email'] =  $userinfo ['email'];
  			
  			//$result = array ('id' => $fbid, 'name' => $userName, 'email' => $email);
  			//checkuser($fuid,$ffname,$email);
  			//$HG -> setContentType ('application/json');
  			//echo json_encode($result);
			header ('Location:' . '/application/public/index.html');
		
		
	
	});

	$HG =& getInstance ();
	$HG -> get ($facebookHandler -> build ());
?>