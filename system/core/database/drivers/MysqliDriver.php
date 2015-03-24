<?php

	class MysqliDriver extends DBdriver {

		public function __construct ($dbConfig) {
			parent::__construct ($dbConfig);
		}

		public function __dbConnect () {
			return mysqli_connect ($this -> hostname, $this -> username, $this -> password, $this -> database);
		}

		public function __execute ($sql) {
			if (is_resource ($this -> dbconn)) echo 'connection';
			return mysqli_query ($this -> dbconn, $sql);
		}

		public function __fetchRow ($resultId) {
			return mysqli_fetch_row ($resultId);	
		}

		public function __fetchAssoc ($resultId) {
			return mysqli_fetch_assoc ($resultId);
		}

		public function __transBegin () {
			$this -> simpleQuery ('SET AUTOCOMMIT = 0');
			$this -> simpleQuery ('START TRANSACTION');
			return true;
		}

		public function __transRollback () {
			$this -> simpleQuery ('ROLLBACK');
			$this -> simpleQuery ('SET AUTOCOMMIT = 1');
			return true;
		}

		public function __transCommit () {
			$this -> simpleQuery ('COMMIT');
			$this -> simpleQuery ('SET AUTOCOMMIT = 1');
			return true;		
		}

		public function __close () {
			return mysqli_close ($this -> dbconn);
		}
	}

?>