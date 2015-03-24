<?php

	class DBdriver {

		protected $dbconn;

		protected $username;
		protected $password;
		protected $hostname;
		protected $database;
		protected $drivername;
		protected $charset	= 'utf8';
		protected $dbcollation = 'utf8_general_ci';

		protected $transStart = false;
		protected $transStatus = true;

		public function __construct ($dbConfig) {
			if (is_array ($dbConfig)) {
				foreach ($dbConfig as $property => $value) {
					$this -> $property = $value;
				}
			}
		}

		private function init () {
			if (is_resource ($this -> dbconn)) {
				return true;
			}

			$this -> dbconn = $this -> __dbConnect ();

			if (!is_resource ($this -> dbconn) && !is_object ($this -> dbconn)) return false;
			return true;
		}

		public function query ($sql) {
			if ($sql == '') return false;
			// run the query
			if  (!$resultId = $this -> simpleQuery ($sql)) {
				$this -> transStatus = false;
				return false;
			}
			if ($this -> isWriteType ($sql)) {
				return true;
			} else {
				return $resultId;
			}

		}

		// for lazy init.
		protected function simpleQuery ($sql) {
			if (!is_resource ($this -> dbconn) && !is_object($this -> dbconn)) {
				$this -> init ();
			}
			return $this -> __execute ($sql);
		}

		public function fetchRow ($resultId) {
			if (!is_resource ($resultId) && !is_object ($resultId)) return false;
			return $this -> __fetchRow ($resultId);
		}

		public function fetchAssoc ($resultId) {
			if (!is_resource ($resultId) && !is_object ($resultId)) {
				
				return false;
			}
			return $this -> __fetchAssoc ($resultId);
		}

		//transaction start

		public function transStart () {
			if ($this -> transStart) {
				return true;
			}
			$this -> __transBegin ();
			return true;
		}

		public function transComplete () {
			if (!$this -> transStart) return false;
			if (!$this -> transStatus) {
				$this -> __transRollback ();
				// init status;
				$this -> transStatus = true;
				$this -> transStart = false;
			}
			$this -> __transCommit ();
			return true;
		}

		//transaction end

		private function isWriteType ($sql) {
			if ( ! preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $sql))
			{
				return FALSE;
			}
			return TRUE;
		}

		public function close () {
			if (!is_resource ($this -> dbconn)) return true;
			return $this -> __close ();
		}

	}

?>