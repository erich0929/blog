<?php

	class CommentsMapper {

		private $HG;
		private $driver;
		private $facebook;

		public function __construct () {
			$this -> HG =& getInstance ();
			$this -> driver = $this -> HG -> loader -> database ('MysqliDriver');
		}

		public function getComments ($boardName, $articleId) {
			$sql = "SELECT * FROM `Comments` WHERE `boardName` = '{$boardName}' " .
			 "AND `articleId` = {$articleId} ORDER BY `date` ASC";
			$records = $this -> getResultByArray ($sql);
			$result = array ();
//			print_r ($records);
			foreach ($records as $record) {
				array_push ($result, $record);
				$sql = "SELECT * FROM `ReplyComments` WHERE `commentId` = {$record ['commentId']} ORDER BY `date` ASC";
				$replys = $this -> getResultByArray ($sql);
				foreach ($replys as $reply) {
					array_push ($result, $reply);
				}
			}
			return $result;
		}

		public function postComment ($boardName, $articleId, $comment, $sns, $userinfo) {
			$sql = "INSERT INTO `Comments` (`boardName`, `articleId`, `comment`, `snsName`, `userName`, `email`, `date`) " .
					"VALUES ('{$boardName}', '{$articleId}', '{$comment}', '{$sns}', '{$userinfo ['userName']}', '{$userinfo ['email']}', unix_timestamp(now()))";
		
			return $this -> driver -> query ($sql);
		}
		
		public function postReply ($commentId, $comment, $sns, $userinfo) {
			$sql = "INSERT INTO `ReplyComments` (`commentId`, `comment`, `snsName`, `userName`, `email`, `date`) " . 
					"VALUES ('{$commentId}', '{$comment}', '{$sns}', '{$userinfo ['userName']}', '{$userinfo ['email']}', unix_timestamp(now()))";
			return $this -> driver -> query ($sql);
		}

		private function getResultByArray ($sql) {
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