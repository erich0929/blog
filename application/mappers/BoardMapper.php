<?php

	class BoardMapper {

		private $driver;

		public function __construct () {
			$HG =& getInstance ();
			$this -> driver = $HG -> loader -> database ('MysqliDriver');
		}

		public function getBoards () {
			$resultId = $this -> driver -> query ('SELECT * FROM `Board`');
			if (!is_resource ($resultId) && !is_object ($resultId)) {
				return false;
			}
			$boards = array ();
			while ($board = $this -> driver -> fetchAssoc ($resultId)) {
				array_push ($boards, $board);

			}
			return $boards;
		}

		public function getAllArticles ($id, $limit) {
			
			$sql = "SELECT * FROM `Articles`";
			if ($id) {
				$sql = $sql . " WHERE `articleId` < {$id}";
			}

			$sql = $sql . " ORDER BY `articleId` DESC";
			if ($limit) {
				$sql = $sql . " limit {$limit}";
			}

			return $this -> getResultByArray ($sql);
		}

		public function getArticlesByBoard ($boardName, $id, $limit) {
			$sql = "SELECT * FROM `Articles` WHERE `boardName` = '{$boardName}'";
			if ($id) {
				$sql = $sql . " AND `articleId` < {$id}";
			}

			$sql = $sql . " ORDER BY `articleId` DESC";
			if ($limit) {
				$sql = $sql . " limit {$limit}";
			}
			return $this -> getResultByArray ($sql);
		}

		public function removeArticle ($boardName, $articleId) {
			$sql = "DELETE FROM `Articles` WHERE boardName = '{$boardName}' AND articleId = {$articleId}";
			return $this -> driver -> query ($sql);
		}

		public function findOneArticle ($boardName, $articleId) {
			$sql = "SELECT * FROM `Articles` WHERE boardName = '{$boardName}' AND articleId = {$articleId}";
			$resultId = $this -> driver -> query ($sql);
			$record = $this -> driver -> fetchAssoc ($resultId);
			return $record;
		}

		public function editArticle ($boardName, $articleId, $article) {
			$sql = "UPDATE Articles SET `boardName` = '{$article ['boardName']}'," .
					" `title` = '{$article ['title']}', `author` = '{$article ['author']}'," .
					" `content` = '{$article ['content']}'" .
					" WHERE `boardName` = '{$boardName}' AND `articleId` = {$articleId}";
			//echo $sql;
			return $this -> driver -> query ($sql);
		}

		public function createBoard ($boardName, $description) {
			$sql = "INSERT INTO `Board` (name, description) VALUES ('{$boardName}', '{$description}') ";
			return $this -> driver -> query ($sql);
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