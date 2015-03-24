<?php

	class ArticleMapper {

		private $driver;

		public function __construct () {

			$HG = getInstance ();
			$this -> driver = $HG -> loader -> database ('MysqliDriver');

		}

		/*
		 *	 blog.Articles :
		 *
		 *   +-----------+------------------+------+-----+---------+----------------+
		 *	 | Field     | Type             | Null | Key | Default | Extra          |
		 *	 +-----------+------------------+------+-----+---------+----------------+
		 *	 | boardName | varchar(30)      | NO   | PRI | NULL    |                |
		 *	 | articleId | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
		 *	 | title     | varchar(30)      | NO   |     | NULL    |                |
		 *	 | author    | varchar(30)      | NO   |     | NULL    |                |
		 *	 | content   | text             | NO   |     | NULL    |                |
		 *	 | date      | int(10) unsigned | NO   |     | NULL    |                |
		 *	 +-----------+------------------+------+-----+---------+----------------+
		 */

		public function insertArticle ($article) {

			// todo : validate $article
			$sql = "INSERT INTO Articles (boardName, title, author, content, date) VALUES (" .
					"'{$article ['boardName']}', '{$article ['title']}', '{$article ['author']}'," .
					"'{$article ['content']}', unix_timestamp(now()))";
			return $this -> driver -> query ($sql);

		}

		public function findOneArticle ($articleId) {

			$sql = "SELECT * FROM Articles WHERE articleId = {$articleId}";

			$resultId = $this -> driver -> query ($sql);

			if (!is_resource ($resultId) AND !is_object($resultId)) {
				return false;
			}

			$article = $this -> driver -> fetchAssoc ($resultId);
			return $article;
		}
	}

?>