<?php
	
	$facebookConfig = array ();
	$facebookConfig ['appId'] = '807361542684806';
	$facebookConfig ['appSecret'] = 'ceeb8cafd04c9da9c2bff0176ab3f58a';

	$readCommentHandler = new Handler ();
	$readCommentHandler -> rules (array ('/^comments$/'))
	-> handler (function () {
		$HG =& getInstance ();
		$boardName = $_GET ['boardName'];
		$articleId = $_GET ['articleId'];
		
		// TODO : validate $boardName, $articleId
		/*
		 *
		 */
		$commentsMapper = $HG -> loader -> mapper ('CommentsMapper');
		$result = $commentsMapper -> getComments ($boardName, $articleId);
		//print_r ($result);
		$HG -> setContentType ('application/json');
		echo json_encode ($result);
		
	});
	$HG =& getInstance ();
	$HG -> get ($readCommentHandler -> build ());

	$postCommentHandler = new Handler ();
	$postCommentHandler -> rules (array ('/^comment$/'))
	-> handler (function () use (&$facebookConfig) {
		$HG = getInstance (); 		
		$HG -> setContentType ('application/json');
		$accessToken = $_POST ['accessToken'];
		$sns = $_POST ['sns'];
		$boardName = $_POST ['boardName'];
		$articleId = $_POST ['articleId'];
		$comment = $_POST ['message'];
		if ($sns == 'facebook') {
			$facebookMapper = $HG -> loader -> mapper ('FacebookMapper');
			// print_r ($facebookConfig);
			$facebookMapper -> makeSession ($facebookConfig ['appId'], $facebookConfig ['appSecret'], $accessToken);
			$userinfo = $facebookMapper -> getUser ();

			$commentsMapper = $HG -> loader -> mapper ('CommentsMapper');
			if ($_POST ['isReply']) {
				$commentMapper -> postReply ($_POST ['commentId'], $comment, $sns, $userinfo);
			} else {
				$isSuccess = $commentsMapper -> postComment ($boardName, $articleId, $comment, $sns, $userinfo);
			}
			
			if ($isSuccess) {
				$_SESSION ['userName'] = $userinfo ['userName'];
				$_SESSION ['email'] = $userinfo ['email'];
				$_COOKIES ['userName'] = $userinfo ['userName'];
				$_COOKIES ['email'] = $userinfo ['email'];

				$result = array ();
				$result ['comment'] = $comment;
				$result ['userName'] = $userinfo ['userName'];
				$result ['email'] = $userinfo ['email'];
				$result ['date'] = time ();
				$result ['status'] = TRUE;
				$result ['sql'] = $sql;
				echo json_encode($result, JSON_FORCE_OBJECT);
				//header ('Location: ' . $HG -> domain . '/application/public/http://localhost/application/public/index.html#/view/' . $articleId);
				return;
			};
		}
		echo json_encode(array ('status' => FALSE));
	});
	$HG =& getInstance ();
	$HG -> post ($postCommentHandler -> build ());

?>