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

	class FacebookMapper {

		private $driver;
		private $tableName;
		private $accessToken;
		private $session;
		private $fbid;

		public function __construct () {

			$this -> HG =& getInstance ();
			$this -> driver = $this -> HG -> loader -> database ('MysqliDriver');
			$this -> tableName = 'facebookComments';
			// makeSession logic go to function makeSession due to loader' mapper function interface.
			// if you have access token, 
			// you should call makeSession function after getting instance from loader mapper function.

		}

		public function getPostId ($commentId) {
			$sql = "SELECT `postId` FROM {$this -> tableName} WHERE `commentId` = $commentId";
			$res = getResultByArray ($sql);
			return $res [0]['postId'];
		}

		public function makeSession ($appId, $appSecret, $accessToken) {
			FacebookSession::setDefaultApplication ($appId, $appSecret);
			$this -> accessToken = $accessToken;
			$this -> session = new FacebookSession($this -> accessToken);
			// TODO : !isset ($session) then throw Exception; 
		}

		public function getUser () {
			if (!isset($this -> session)) return false;
			$graphObject = $this -> graphRequest ('GET', '/me');
			$fbid = $graphObject -> getProperty ('id');
			$this -> fbid = $fbid;
			$userName = $graphObject -> getProperty ('name');
			$email = $graphObject -> getProperty ('email');
			return array ('fbid' => $fbid, 'userName' => $userName, 'email' => $email);
		}

		// this function may delete record that have $postId if there is no $postId in facebook.	
		public function getComments ($postId) {
			$comments = array ();
			
			if (count ($comments)) $this -> removeRecords ($postId);
		}

		private function removeRecords ($postId) {
			$sql = "DELETE FROM {$this -> tableName} WHERE `postId` = {$postId}";
			$this -> driver -> query ($sql);
		}

		public function postFeed ($comment, $link) {
			$request = new FacebookRequest($session, 'POST', "/" . $this -> fbid . "/feed",
   				 array (
    				'message' => $comment,
    				'link' => $link
  				)
			);
			$response = $request -> execute();
			$graphObject = $response -> getGraphObject();
			return $graphObject -> getProperty ('id');
		}

		private function graphRequest ($method, $endpoint) {
			$request = new FacebookRequest( $this -> session, $method, $endpoint);
			$response = $request -> execute();
				
  			// get response
			$graphObject = $response -> getGraphObject();
			return $graphObject;
		}

		private function getResultByArray ($sql) {
			//
			$resultId = $this -> driver -> query ($sql);
			if (!is_resource ($resultId) && !is_object ($resultId)) {
				return false;
			}
			$records = array ();
			while ($record = $this -> driver -> fetchAssoc ($resultId)) {
				array_push ($records, $record);
			}
			return $records;
		}

	}

?>